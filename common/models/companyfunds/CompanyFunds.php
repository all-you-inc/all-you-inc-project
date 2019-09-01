<?php

namespace common\models\companyfunds;

use shop\entities\Shop\Product\Product;
use Yii;

/**
 * This is the model class for table "company_funds".
 *
 * @property integer $id
 * @property integer $shop_product_id
 * @property integer $referral_code
 * @property string $type
 * @property integer $ref_id
 * @property string $amount
 * @property string $created_at
 * @property string $created_by
 * @property string $modified_at
 * @property string $modified_by
 * @property integer $is_deleted
 *
 * @property Product $product
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
            [['shop_product_id', 'referral_code'], 'required'],
            [['shop_product_id', 'referral_code', 'ref_id', 'created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['type'], 'string'],
            [['amount'], 'string', 'max' => 64],
            [['shop_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['shop_product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'shop_product_id' => 'Shop Product ID',
            'referral_code' => 'Referral Code',
            'type' => 'Type',
            'ref_id' => 'Ref ID',
            'amount' => 'Amount',
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
    public function getShopProduct() {
        return $this->hasOne(Product::className(), ['id' => 'shop_product_id']);
    }

}