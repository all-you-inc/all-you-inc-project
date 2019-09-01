<?php

namespace common\models\payment;

use Yii;
use common\models\currency\Currency;
use shop\entities\User\User;

/**
 * This is the model class for table "payment".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $payment_method
 * @property string $amount
 * @property integer $currency_id
 * @property string $transection_id
 * @property integer $status
 * @property string $type
 * @property integer $ref_id
 * @property string $created_at
 * @property string $created_by
 * @property string $modified_at
 * @property string $modified_by
 * @property integer $is_deleted
 *
 * @property User $user
 * @property Currency $currency
 */
class Payment extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'payment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id', 'currency_id'], 'required'],
            [['user_id', 'currency_id', 'status', 'ref_id', 'created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['payment_method', 'type'], 'string'],
            [['amount'], 'number'],
            [['transection_id'], 'string', 'max' => 128],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'payment_method' => 'Payment Method',
            'amount' => 'Amount',
            'currency_id' => 'Currency ID',
            'transection_id' => 'Transection ID',
            'status' => 'Status',
            'type' => 'Type',
            'ref_id' => 'Ref ID',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency() {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

}
