<?php

namespace common\models\userreferral;
use shop\entities\User\User;
use Yii;

/**
 * This is the model class for table "user_referral".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $referral_user_id
 * @property string $referral_code
 * @property integer $tier
 * @property string $created_at
 * @property string $created_by
 * @property string $modified_at
 * @property string $modified_by
 * @property integer $is_deleted
 *
 * @property User $user
 * @property User $referralUser
 */
class UserReferral extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_referral';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'referral_user_id', 'referral_code', 'tier'], 'required'],
            [['user_id', 'referral_user_id', 'tier', 'created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['referral_code'], 'string', 'max' => 32],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['referral_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['referral_user_id' => 'id']],
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
            'referral_user_id' => 'Referral User ID',
            'referral_code' => 'Referral Code',
            'tier' => 'Tier',
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
    public function getReferralUser()
    {
        return $this->hasOne(User::className(), ['id' => 'referral_user_id']);
    }
}
