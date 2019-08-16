<?php

namespace common\models\talentmaster;

use Yii;

/**
 * This is the model class for table "talent_master".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Industry2TalentMaster[] $industry2TalentMasters
 */
class TalentMaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'talent_master';
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
        return $this->hasMany(Industry2TalentMaster::className(), ['talent_master_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return TalentMasterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TalentMasterQuery(get_called_class());
    }
}
