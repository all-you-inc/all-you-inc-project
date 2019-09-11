<?php

namespace common\models\userfunds;

use shop\entities\User\User;
use shop\entities\Shop\Product\Product;
use Yii;

/**
 * This is the model class for table "user_funds".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string  $referral_code
 * @property string $type
 * @property integer $ref_id
 * @property string $amount
 * @property string $transaction_id 
 * @property string $created_at
 * @property string $created_by
 * @property string $modified_at
 * @property string $modified_by
 * @property integer $is_deleted
 *
 * @property User $user
 */
class UserFunds extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user_funds';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id', 'referral_code', 'transaction_id'], 'required'],
            [['user_id', 'ref_id', 'created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['type'], 'string'],
            [['referral_code', 'transaction_id'], 'string', 'max' => 32],
            [['amount'], 'number'],
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
            'referral_code' => 'Referral Code',
            'type' => 'Type',
            'ref_id' => 'Ref ID',
            'amount' => 'Amount',
            'transaction_id' => 'Transaction ID',
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
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

}
