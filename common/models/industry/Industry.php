<?php

namespace common\models\industry;

use Yii;
use common\models\industry2talentmaster\Industry2TalentMaster;

/**
 * This is the model class for table "industry".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Industry2TalentMaster[] $industry2TalentMasters
 */
class Industry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'industry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndustry2TalentMasters()
    {
        return $this->hasMany(Industry2TalentMaster::className(), ['industry_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return IndustryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new IndustryQuery(get_called_class());
    }
}
