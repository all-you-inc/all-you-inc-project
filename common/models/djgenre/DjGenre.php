<?php

namespace common\models\djgenre;

use Yii;

/**
 * This is the model class for table "dj_genre".
 *
 * @property integer $id
 * @property string $name
 */
class DjGenre extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dj_genre';
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
     * @inheritdoc
     * @return DjGenreQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DjGenreQuery(get_called_class());
    }
}
