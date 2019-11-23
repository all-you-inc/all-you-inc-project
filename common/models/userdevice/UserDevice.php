<?php

namespace common\models\userdevice;

use shop\entities\User\User;


/**
 * This is the model class for table "user_devices".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $token
 * @property string $type
 * @property string $created_at
 * @property string $modified_at
 *
 * @property User $user
 */
class UserDevice extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user_devices';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['type', 'token'], 'required'],
            [['user_id',  'created_at', 'modified_at'], 'integer'],
            [['type'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
          
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'token' => 'Device Token',
            'type' => 'Type',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    
}
