<?php

namespace common\models\instrument;

use Yii;

/**
 * This is the model class for table "instrument".
 *
 * @property integer $id
 * @property string $name
 *
 * @property InstrumentSpecification[] $instrumentSpecifications
 */
class Instrument extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'instrument';
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
    public function getInstrumentSpecifications()
    {
        return $this->hasMany(InstrumentSpecification::className(), ['instrument_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return InstrumentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InstrumentQuery(get_called_class());
    }
}
