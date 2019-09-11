<?php

namespace common\models\membership;
use common\models\currency\Currency;
use Yii;

/**
 * This is the model class for table "membership".
 *
 * @property integer $id
 * @property string $title
 * @property integer $sort
 * @property string $price
 * @property integer $currency_id
 * @property string $status
 * @property string $description
 * @property string $category
 * @property string $created_at
 * @property string $created_by
 * @property string $modified_at
 * @property string $modified_by
 * @property integer $is_deleted
 *
 * @property Currency $currency
 * @property MsItems[] $msItems
 */
class Membership extends \yii\db\ActiveRecord
{
      const Talent = 1;
      const TalentWithProduct = 2;
      const Promoter = 3;
      const FanId = 4;
      const CustomerId = 5;
      const FreeTalent = 6;
      const FreeTalentWithProduct = 7;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'membership';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort', 'currency_id', 'created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['status', 'description', 'category'], 'string'],
            [['title'], 'string', 'max' => 256],
            [['price'], 'string', 'max' => 64],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
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
            'sort' => 'Sort',
            'price' => 'Price',
            'currency_id' => 'Currency ID',
            'status' => 'Status',
            'description' => 'Description',
            'category' => 'Category',
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
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMsItems()
    {
        return $this->hasMany(MsItems::className(), ['membership_id' => 'id']);
    }
}
