<?php

namespace common\models\notification;

use Yii;
use shop\entities\User\User;
use common\models\notification\NotificationEvents;
/**
 * This is the model class for table "user_notification".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $notification_event_id
 * @property integer $email
 * @property integer $web
 * @property integer $mobile
 * @property string $created_at
 * @property integer $created_by
 * @property string $modified_at
 * @property integer $modified_by
 * @property integer $is_deleted
 *
 * @property User $user
 * @property NotificationEvents $notificationEvent
 */
class UserNotification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'notification_event_id'], 'required'],
            [['user_id', 'notification_event_id', 'email', 'web', 'mobile', 'created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['notification_event_id'], 'exist', 'skipOnError' => true, 'targetClass' => NotificationEvents::className(), 'targetAttribute' => ['notification_event_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'notification_event_id' => 'Notification Event ID',
            'email' => 'Email',
            'web' => 'Web',
            'mobile' => 'Mobile',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'modified_at' => 'Modified At',
            'modified_by' => 'Modified By',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationEvent()
    {
        return $this->hasOne(NotificationEvents::className(), ['id' => 'notification_event_id']);
    }
}
