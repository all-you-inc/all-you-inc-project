<?php

namespace common\models\userfans;

use shop\entities\User\User;
use common\models\usertalent\UserTalent;
use Yii;

/**
 * This is the model class for table "user_fans".
 *
 * @property integer $id
 * @property integer $user_talent_id
 * @property integer $fan_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $modified_at
 * @property integer $modified_by
 * @property integer $is_deleted
 *
 * @property UserTalent $userTalent
 * @property User $fan
 * @property User $createdBy
 * @property User $modifiedBy
 */
class UserFans extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_fans';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_talent_id', 'fan_id', 'created_by', 'modified_by'], 'required'],
            [['user_talent_id', 'fan_id', 'created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['user_talent_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserTalent::className(), 'targetAttribute' => ['user_talent_id' => 'id']],
            [['fan_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['fan_id' => 'id']],
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
            'user_talent_id' => 'User Talent ID',
            'fan_id' => 'Fan ID',
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
    public function getUserTalent()
    {
        return $this->hasOne(UserTalent::className(), ['id' => 'user_talent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFan()
    {
        return $this->hasOne(User::className(), ['id' => 'fan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifiedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'modified_by']);
    }
}
