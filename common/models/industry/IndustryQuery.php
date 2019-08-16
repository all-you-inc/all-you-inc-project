<?php

namespace common\models\industry;

/**
 * This is the ActiveQuery class for [[Industry]].
 *
 * @see Industry
 */
class IndustryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Industry[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Industry|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
