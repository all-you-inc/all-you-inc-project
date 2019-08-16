<?php

namespace common\models\industry2talentmaster;

use common\models\industry\Industry;
use common\models\talentmaster\TalentMaster;
use Yii;

/**
 * This is the model class for table "industry_2_talent_master".
 *
 * @property integer $id
 * @property integer $industry_id
 * @property integer $talent_master_id
 *
 * @property Industry $industry
 * @property TalentMaster $talentMaster
 */
class Industry2TalentMaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'industry_2_talent_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['industry_id', 'talent_master_id'], 'required'],
            [['industry_id', 'talent_master_id'], 'integer'],
            [['industry_id'], 'exist', 'skipOnError' => true, 'targetClass' => Industry::className(), 'targetAttribute' => ['industry_id' => 'id']],
            [['talent_master_id'], 'exist', 'skipOnError' => true, 'targetClass' => TalentMaster::className(), 'targetAttribute' => ['talent_master_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'industry_id' => 'Industry ID',
            'talent_master_id' => 'Talent Master ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndustry()
    {
        return $this->hasOne(Industry::className(), ['id' => 'industry_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTalentMaster()
    {
        return $this->hasOne(TalentMaster::className(), ['id' => 'talent_master_id']);
    }

    /**
     * @inheritdoc
     * @return Industry2TalentMasterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new Industry2TalentMasterQuery(get_called_class());
    }
}
