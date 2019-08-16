<?php

namespace common\models\industry2talentmaster;

/**
 * This is the ActiveQuery class for [[Industry2TalentMaster]].
 *
 * @see Industry2TalentMaster
 */
class Industry2TalentMasterQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Industry2TalentMaster[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Industry2TalentMaster|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
