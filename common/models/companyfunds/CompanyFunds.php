<?php

namespace common\models\companyfunds;

use shop\entities\Shop\Product\Product;
use Yii;

/**
 * This is the model class for table "company_funds".
 *
 * @property integer $id
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
 */
class CompanyFunds extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'company_funds';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['transaction_id'], 'required'],
            [['type'], 'string'],
            [['ref_id', 'created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['referral_code', 'transaction_id'], 'string', 'max' => 32],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
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

}
