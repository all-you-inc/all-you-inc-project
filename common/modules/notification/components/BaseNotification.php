<?php

namespace common\modules\notification\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap\Html;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\mail\MessageInterface;
use common\modules\notification\components\SocialActivity;
use common\modules\notification\jobs\SendBulkNotification;
use common\modules\notification\jobs\SendNotification;
use common\modules\notification\models\Notification;
use common\modules\notification\targets\BaseTarget;
use common\modules\notification\targets\WebTarget;
use common\modules\user\components\ActiveQueryUser;
// use shop\entities\User\User;
use shop\entities\User\User;

/**
 * A BaseNotification class describes the behaviour and the type of a Notification.
 * A BaseNotification is created and can be sent to one or multiple users over different targets.
 *
 * The BaseNotification can should be created like this:
 *
 * MyNotification::instance()->from($originator)->about($source)->sendBulk($activeQueryUser);
 *
 * This will send Notifications to different notification targets by using a queue.
 *
 * @property Notification $record
 * @author luke
 */
abstract class BaseNotification extends SocialActivity
{

    /**
     * @var boolean automatically mark notification as seen after click on it
     */
    public $markAsSeenOnClick = true;

    /**
     * @var int number of combined notifications
     */
    public $groupCount = 0;

    /**
     * @since 1.2.3
     * @see NotificationManager
     * @var boolean do not send this notification also to the originator
     */
    public $suppressSendToOriginator = true;

    /**
     * @var string the group key
     */
    protected $_groupKey = null;

    /**
     * @var \common\modules\notification\components\NotificationCategory cached category instance
     */
    protected $_category = null;

    /**
     * @inheritdoc
     */
    public $recordClass = Notification::class;

    /**
     * Priority flag, if set to true, this Notification type will be marked as high priority.
     * This can be used by a given BaseTarget while handling a Notification.
     *
     * A MobileTargetProvider for example could use this flag for Android devices to wake up the device out of doze mode.
     *
     * @var bool if set to true marks this notification type as high priority.
     * @since 1.2.3
     */
    public $priority = false;

    /**
     * Returns the notification category instance. If no category class is set (default) the default notification settings
     * can't be overwritten.
     *
     * The category instance is cached, once created.
     *
     * If the Notification configuration should be configurable subclasses have to overwrite this method.
     *
     * @return \common\modules\notification\components\NotificationCategory
     */
    public function getCategory()
    {
        if (!$this->_category) {
            $this->_category = $this->category();
        }

        return $this->_category;
    }

    /**
     * Returns a new NotificationCategory instance.
     *
     * This function should be overwritten by subclasses to append this BaseNotification
     * to the returned category. If no category instance is returned, the BaseNotification behavriour (targets) will not be
     * configurable.
     *
     * @return \common\modules\notification\components\NotificationCategory
     */
    protected function category()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getViewParams($params = [])
    {
        if ($this->hasContent() && $this->getContent()->updated_at instanceof Expression) {
            $this->getContent()->refresh();
            $date = $this->getContent()->updated_at;
        } elseif ($this->hasContent()) {
            $date = $this->getContent()->updated_at;
        } else {
            $date = null;
        }

        $result = [
            'url' => Url::to(['/notification/entry', 'id' => $this->record->id], true),
            'relativeUrl' => Url::to(['/notification/entry', 'id' => $this->record->id], false),
            'date' => $date,
            'isNew' => !$this->record->seen,
        ];

        return ArrayHelper::merge(parent::getViewParams($result), $params);
    }

    /**
     * Sends this notification to a set of users.
     *
     * Note: For compatibility reasons this method also allows to pass an array of user objects.
     * This support will removed in future versions.
     *
     * @param ActiveQueryUser|array|User[] $query the user query
     * @throws InvalidConfigException
     */
    public function sendBulk($query)
    {
        if (empty($this->moduleId)) {
            throw new InvalidConfigException('No moduleId given for "' . get_class($this) . '"');
        }

        if (!$query instanceof ActiveQueryUser) {
            /** @var array $query */
            Yii::debug('BaseNotification::sendBulk - pass ActiveQueryUser instead of array!', 'notification');

            // Migrate given array to ActiveQueryUser
            $query = User::find()->where(['IN', 'user.id', array_map(function ($user) {
                if ($user instanceof User) {
                    return $user->id;
                }
                // User id
                return $user;
            }, $query)]);
        }

        Yii::$app->queue->push(new SendBulkNotification(['notification' => $this, 'query' => $query]));
    }

    /**
     * Sends this notification to all notification targets of the given User.
     * This function will not send notifications to the originator itself.
     *
     * @param User $user
     * @throws InvalidConfigException
     */
    public function send(User $user)
    {
        if (empty($this->moduleId)) {
            throw new InvalidConfigException('No moduleId given for "' . get_class($this) . '"');
        }

        if ($this->isOriginator($user)) {
            return;
        }

        Yii::$app->queue->push(new SendNotification(['notification' => $this, 'recipientId' => $user->id]));
    }

    /**
     * Returns the mail subject which will be used in the notification e-mail
     *
     * @see \common\modules\notification\targets\MailTarget
     * @return string the subject
     */
    public function getMailSubject()
    {
        return 'New notification';
    }

    /**
     * Checks if the given $user is the originator of this notification.
     *
     * @param User $user
     * @return boolean
     */
    public function isOriginator(User $user)
    {
        return $this->originator && $this->originator->id === $user->id;
    }

    /**
     * Creates the Notification instance of the current BaseNotification type for the
     * given $user.
     *
     * @param User $user
     * @return bool
     */
    public function saveRecord(User $user)
    {
        if (!$this->validate()) {
            return false;
        }

        $notification = new Notification([
            'user_id' => $user->id,
            'class' => static::class,
            'module' => $this->moduleId,
            'group_key' => $this->getGroupKey(),
        ]);

        if ($this->source) {
            $notification->setPolymorphicRelation($this->source);
            $notification->space_id = $this->getSpaceId();
        }

        if ($this->originator) {
            $notification->originator_user_id = $this->originator->id;
        }

        if (!$notification->save()) {
            Yii::error(
                'Could not save Notification Record for' .
                static::class . ' ' .
                print_r($notification->getErrors(), true)
            );
            return false;
        }

        $this->record = $notification;

        return true;
    }

    /**
     * @inheritdoc
     */
    public function about($source)
    {
        if (!$source) {
            return $this;
        }
        parent::about($source);
        $this->record->space_id = $this->getSpaceId();

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function from($originator)
    {
        if (!$originator) {
            return $this;
        }
        $this->originator = $originator;
        $this->record->originator_user_id = $originator->id;

        return $this;
    }

    /**
     * Deletes this notification
     */
    public function delete(User $user = null)
    {
        $condition = [];

        $condition['class'] = static::class;

        if ($user !== null) {
            $condition['user_id'] = $user->id;
        }

        if ($this->originator !== null) {
            $condition['originator_user_id'] = $this->originator->id;
        }

        if ($this->source !== null) {
            $condition['source_pk'] = $this->source->getPrimaryKey();
            $condition['source_class'] = $this->source->className();
        }

        Notification::deleteAll($condition);
    }

    /**
     * Marks notification as seen
     */
    public function markAsSeen()
    {
        if ($this->record->group_key != '') {
            // Ensure to update all grouped notifications
            Notification::updateAll([
                'seen' => 1
            ], [
                'class' => $this->record->class,
                'user_id' => $this->record->user_id,
                'group_key' => $this->record->group_key
            ]);
        } else {
            $this->record->seen = 1;
            $this->record->save();
        }

        // Automatically mark similar notifications (same source) as seen
        $similarNotifications = Notification::find()
            ->where(['source_class' => $this->record->source_class, 'source_pk' => $this->record->source_pk, 'user_id' => $this->record->user_id])
            ->andWhere(['!=', 'seen', '1']);
        foreach ($similarNotifications->all() as $notification) {
            /* @var $notification Notification */
            $notification->getBaseModel()->markAsSeen();
        }
    }

    /**
     * Returns a key for grouping notifications.
     * If null is returned (default) the notification grouping for this BaseNotification type disabled.
     *
     * The returned key could for example be a combination of classname related content id.
     *
     * @return string the group key
     */
    public function getGroupKey()
    {
        return null;
    }

    /**
     * Renders the Notificaiton for the given notification target.
     * Subclasses are able to use custom renderer for different targets by overwriting this function.
     *
     * @param BaseTarget $target
     * @return string render result
     */
    public function render(BaseTarget $target = null)
    {
        if (!$target) {
            $target = Yii::$app->notification->getTarget(WebTarget::class);
        }

        return $target->getRenderer()->render($this);
    }

    /**
     * Returns the combined display names of a grouped notification.
     * Examples:
     *      User A and User B
     *      User A and 5 others
     *
     * @return string the display names
     */
    public function getGroupUserDisplayNames()
    {
        if ($this->groupCount > 2) {
            list($user) = $this->getGroupLastUsers(1);
            return Yii::t('NotificationModule.base', '{displayName} and {number} others', [
                'displayName' => Html::tag('strong', Html::encode($user->displayName)),
                'number' => $this->groupCount - 1
            ]);
        }

        list($user1, $user2) = $this->getGroupLastUsers(2);

        return Yii::t('NotificationModule.base', '{displayName} and {displayName2}', [
            'displayName' => Html::tag('strong', Html::encode($user1->displayName)),
            'displayName2' => Html::tag('strong', Html::encode($user2->displayName)),
        ]);
    }

    /**
     * Returns the last users of a grouped notification
     *
     * @param int $limit users to return
     * @return User[] the number of user
     */
    public function getGroupLastUsers($limit = 2)
    {
        $users = [];

        $query = Notification::find()
            ->where([
                'notification.user_id' => $this->record->user_id,
                'notification.class' => $this->record->class,
                'notification.group_key' => $this->record->group_key
            ])
            ->joinWith(['originator', 'originator.profile'])
            ->orderBy(['notification.created_at' => SORT_DESC])
            ->groupBy(['notification.originator_user_id'])
            ->andWhere(['IS NOT', 'user.id', new Expression('NULL')])
            ->limit($limit);

        foreach ($query->all() as $notification) {
            $users[] = $notification->originator;
        }

        return $users;
    }

    /**
     * @inheritdoc
     */
    public function asArray(User $user)
    {
        $result = parent::asArray($user);
        $result['mailSubject'] = $this->getMailSubject($user);

        return $result;
    }

    /**
     * Should be overwritten by subclasses for a html representation of the notification.
     * @return string
     */
    public function html()
    {
        // Only for backward compatibility.
        return $this->getAsHtml();
    }

    /**
     * Use text() instead
     * @deprecated since version 1.2
     */
    public function getAsText()
    {
        return $this->text();
    }

    /**
     * Use html() instead
     * @deprecated since version 1.2
     */
    public function getAsHtml()
    {
        return null;
    }

    /**
     * This method is invoked right before a mail will be send for this notificatoin
     *
     * @see \common\modules\notification\targets\MailTarget
     * @param \yii\mail\MessageInterface $message
     * @return boolean when true the mail will be send
     */
    public function beforeMailSend(MessageInterface $message)
    {
        return true;
    }

}
