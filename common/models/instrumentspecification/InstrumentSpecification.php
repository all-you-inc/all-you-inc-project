<?php

namespace common\models\instrumentspecification;

use Yii;

/**
 * This is the model class for table "instrument_specification".
 *
 * @property integer $id
 * @property string $name
 * @property integer $instrument_id
 *
 * @property Instrument $instrument
 */
class InstrumentSpecification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'instrument_specification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'instrument_id'], 'required'],
            [['instrument_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['instrument_id'], 'exist', 'skipOnError' => true, 'targetClass' => Instrument::className(), 'targetAttribute' => ['instrument_id' => 'id']],
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
            'instrument_id' => 'Instrument ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstrument()
    {
        return $this->hasOne(Instrument::className(), ['id' => 'instrument_id']);
    }

    /**
     * @inheritdoc
     * @return InstrumentSpecificationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InstrumentSpecificationQuery(get_called_class());
    }
}
