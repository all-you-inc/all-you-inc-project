<?php

namespace common\models\talentmaster;

/**
 * This is the ActiveQuery class for [[TalentMaster]].
 *
 * @see TalentMaster
 */
class TalentMasterQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TalentMaster[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TalentMaster|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
