<?php

namespace common\models\membership;

use common\models\membership\Membership;
use common\models\membership\MsItemTypes;
use common\models\currency\Currency;
use Yii;

/**
 * This is the model class for table "ms_items".
 *
 * @property integer $id
 * @property integer $membership_id
 * @property integer $unit
 * @property string $type
 * @property integer $item_type_id
 * @property string $price
 * @property integer $currency_id
 * @property integer $group_id
 * @property string $created_at
 * @property string $created_by
 * @property string $modified_at
 * @property string $modified_by
 * @property integer $is_deleted
 *
 * @property Membership $membership
 * @property MsItemTypes $itemType
 * @property Currency $currency
 */
class MsItems extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_items';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['membership_id', 'item_type_id', 'currency_id', 'group_id'], 'required'],
            [['membership_id', 'unit', 'item_type_id', 'currency_id', 'group_id', 'created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['type'], 'string'],
            [['price'], 'number'],
            [['membership_id'], 'exist', 'skipOnError' => true, 'targetClass' => Membership::className(), 'targetAttribute' => ['membership_id' => 'id']],
            [['item_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MsItemTypes::className(), 'targetAttribute' => ['item_type_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'membership_id' => 'Membership ID',
            'unit' => 'Unit',
            'type' => 'Type',
            'item_type_id' => 'Item Type ID',
            'price' => 'Price',
            'currency_id' => 'Currency ID',
            'group_id' => 'Group ID',
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
    public function getMembership() {
        return $this->hasOne(Membership::className(), ['id' => 'membership_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemType() {
        return $this->hasOne(MsItemTypes::className(), ['id' => 'item_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency() {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

}
