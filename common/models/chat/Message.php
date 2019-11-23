<?php

namespace common\models\chat;

use Yii;
use common\models\chat\Thread;
use common\models\chat\MessageRead;
use shop\entities\User\User;

/**
 * This is the model class for table "chat_message".
 *
 * @property integer $id
 * @property integer $thread_id
 * @property integer $user_id
 * @property string $body
 * @property string $created_at
 * @property integer $created_by
 * @property string $modified_at
 * @property integer $modified_by
 * @property integer $is_deleted
 *
 * @property User $createdBy
 * @property User $modifiedBy
 * @property Thread $thread
 * @property User $user
 */
class Message extends \yii\db\ActiveRecord {

    public $my_count;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'chat_message';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['thread_id', 'user_id', 'created_by', 'modified_by'], 'required'],
            [['thread_id', 'user_id', 'created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['body'], 'string'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
            [['thread_id'], 'exist', 'skipOnError' => true, 'targetClass' => Thread::className(), 'targetAttribute' => ['thread_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'thread_id' => 'Thread ID',
            'user_id' => 'User ID',
            'body' => 'Body',
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
    public function getCreated() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModified() {
        return $this->hasOne(User::className(), ['id' => 'modified_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThread() {
        return $this->hasOne(Thread::className(), ['id' => 'thread_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getMessageReads() {
        return $this->hasMany(MessageRead::className(), ['id' => 'message_id']);
    }

    public static function getNotificationText() {
        return 'Message Notification Text';
    }

}
