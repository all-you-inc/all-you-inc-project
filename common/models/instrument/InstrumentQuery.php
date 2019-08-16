<?php

namespace common\models\instrument;

/**
 * This is the ActiveQuery class for [[Instrument]].
 *
 * @see Instrument
 */
class InstrumentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Instrument[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Instrument|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
