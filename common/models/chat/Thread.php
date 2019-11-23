<?php

namespace common\models\chat;

use Yii;
use shop\entities\User\User;
use common\models\chat\Message;
use common\models\chat\ThreadParticipant;

/**
 * This is the model class for table "chat_thread".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $created_at
 * @property integer $created_by
 * @property string $modified_at
 * @property integer $modified_by
 * @property integer $is_deleted
 *
 * @property Message[] $Messages
 * @property User $created
 * @property User $modified
 * @property ThreadParticipant[] $chatThreadParticipants
 */
class Thread extends \yii\db\ActiveRecord
{
    public $readCount;
    public $messageCount;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chat_thread';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'created_by', 'modified_by'], 'required'],
            [['description'], 'string'],
            [['created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['title'], 'string', 'max' => 256],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
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
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['thread_id' => 'id']);
    }
    
    public function getchat_message_read(){
        return $this->hasMany(Message::className(), ['thread_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreated()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModified()
    {
        return $this->hasOne(User::className(), ['id' => 'modified_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChatThreadParticipants()
    {
        return $this->hasMany(ThreadParticipant::className(), ['thread_id' => 'id']);
    }
}
