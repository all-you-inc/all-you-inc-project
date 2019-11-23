<?php

namespace common\models\notification;

use Yii;

/**
 * This is the model class for table "notification_events".
 *
 * @property integer $id
 * @property string $event
 * @property string $key
 * @property string $describtion
 * @property string $created_at
 * @property integer $created_by
 * @property string $modified_at
 * @property integer $modified_by
 * @property integer $is_deleted
 *
 * @property UserNotification[] $userNotifications
 */
class NotificationEvents extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_events';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event', 'key'], 'required'],
            [['describtion'], 'string'],
            [['created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['event', 'key'], 'string', 'max' => 255],
            [['key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event' => 'Event',
            'key' => 'Key',
            'describtion' => 'Describtion',
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
    public function getUserNotifications()
    {
        return $this->hasMany(UserNotification::className(), ['notification_event_id' => 'id']);
    }
}
