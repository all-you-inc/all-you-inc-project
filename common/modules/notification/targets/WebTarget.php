<?php


namespace common\modules\notification\targets;

use Yii;
use yii\base\Exception;
use shop\entities\User\User;
use common\modules\notification\components\BaseNotification;
use common\modules\notification\live\NewNotification;

/**
 * Web Target
 * 
 * @since 1.2
 * @author buddha
 */
class WebTarget extends BaseTarget
{

    /**
     * @inheritdoc
     */
    public $id = 'web';

    /**
     * @inheritdoc
     */
    public $defaultSetting = true;

    /**
     * Handles Webnotifications by setting the send_web_notifications flag and sending an live event.
     */
    public function handle(BaseNotification $notification, User $user)
    {
        if (!$notification->record) {
            throw new Exception('Notification record not found for BaseNotification "' . $notification->className() . '"');
        }
d('In web Target');
// dd(Yii::$app->controller->render('@common/modules/'.$notification->moduleId.'/views/'.$notification->viewName));
        $notification->record->send_web_notifications = true;
        $notification->record->save();
d('$notification->record->id => '.$notification->record->id);
$msg = Yii::$app->controller->renderPartial('@common/modules/'.$notification->moduleId.'/views/'.$notification->viewNameText, ['originator' => $notification->originator, 'user' => $user, 'source' => $notification->source]);
d('$msg ==> '.$msg);
// d('$notification->getGroupKey() => '.$notification->getGroupKey());
// d('$user->contentcontainer_id => '.$user->contentcontainer_id);
// d('$notification->text() => '.Yii::$app->controller->render('@common/modules/'.$notification->moduleId.'/views/'.$notification->viewName));
        Yii::$app->live->send(new NewNotification([
            'notificationId' => $notification->record->id,
            // 'notificationGroup' => ($notification->getGroupKey()) ? (get_class($notification).':'.$notification->getGroupKey()) : null,
            // 'contentContainerId' => $user->contentcontainer_id,
            'ts' => time(),
            'text' => $msg
        ]));
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return 'Web';
        return Yii::t('NotificationModule.targets', 'Web');
    }

}
