<?php 

namespace common\services;

use Yii;
use common\models\musicgenre\MusicGenre;
use common\models\musicgenre\MusicGenreQuery;


class MusicGenreService {

    public static function getAll(){
        return  MusicGenre::find()->all();
    }

}

class MusicGenreDefinition {
    
    public static function setDefination($musicGenre){
        return [
            'id' => $musicGenre->id,
            'music_genre_name' => $musicGenre->name
        ];
    }

}