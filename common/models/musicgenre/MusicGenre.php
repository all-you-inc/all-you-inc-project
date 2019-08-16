<?php

namespace common\models\musicgenre;

use Yii;

/**
 * This is the model class for table "music_genre".
 *
 * @property integer $id
 * @property string $name
 */
class MusicGenre extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'music_genre';
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
     * @return MusicGenreQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MusicGenreQuery(get_called_class());
    }
}
