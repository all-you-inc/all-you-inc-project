<?php

namespace common\models\musicgenre;

/**
 * This is the ActiveQuery class for [[MusicGenre]].
 *
 * @see MusicGenre
 */
class MusicGenreQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return MusicGenre[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MusicGenre|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
