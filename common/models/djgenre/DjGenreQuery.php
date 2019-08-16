<?php

namespace common\models\djgenre;

/**
 * This is the ActiveQuery class for [[DjGenre]].
 *
 * @see DjGenre
 */
class DjGenreQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return DjGenre[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DjGenre|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
