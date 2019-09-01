<?php

namespace common\models\addons;

use Yii;
use common\models\currency\Currency;

/**
 * This is the model class for table "addons".
 *
 * @property integer $id
 * @property integer $unit
 * @property string $type
 * @property string $price
 * @property integer $currency_id
 * @property string $created_at
 * @property string $created_by
 * @property string $modified_at
 * @property string $modified_by
 * @property integer $is_deleted
 *
 * @property Currency $currency
 */
class Addons extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'addons';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['unit', 'currency_id', 'created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['type'], 'string'],
            [['price'], 'number'],
            [['currency_id'], 'required'],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'unit' => 'Unit',
            'type' => 'Type',
            'price' => 'Price',
            'currency_id' => 'Currency ID',
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
    public function getCurrency() {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

}
