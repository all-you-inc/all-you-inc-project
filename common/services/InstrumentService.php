<?php 

namespace common\services;

use Yii;
use common\models\instrument\Instrument;
use common\models\instrument\InstrumentQuery;


class InstrumentService {

    public static function getAll(){
        return Instrument::find()->all(); 
    } 
}


class InstrumentDefinition {
    
    public static function setDefination($instrument){
        return [
            'id' => $instrument->id,
            'instrument_name' => $instrument->name
        ];
    }

}
