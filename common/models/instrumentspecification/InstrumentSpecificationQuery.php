<?php

namespace common\models\instrumentspecification;

/**
 * This is the ActiveQuery class for [[InstrumentSpecification]].
 *
 * @see InstrumentSpecification
 */
class InstrumentSpecificationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return InstrumentSpecification[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return InstrumentSpecification|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
