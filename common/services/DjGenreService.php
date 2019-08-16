<?php 

namespace common\services;

use Yii;
use common\models\djgenre\DjGenre;
use common\models\djgenre\DjGenreQuery;


class DjGenreService {

    public static function getAll(){
        return  DjGenre::find()->all();
    }

}

class DjGenreDefinition {
    
    public static function setDefination($djGenre){
        return [
            'id' => $djGenre->id,
            'dj_genre_name' => $djGenre->name
        ];
    }

}