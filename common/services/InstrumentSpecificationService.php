<?php 

namespace common\services;

use Yii;
use common\models\instrumentspecification\InstrumentSpecification;
use common\models\instrumentspecification\InstrumentSpecificationQuery;


class InstrumentSpecificationService {

    public static function getInstrumentSpecificationRecordByInstrumentId($id){
        return  InstrumentSpecification::find()->where(['instrument_id' => $id])->all();
    }

}

class InstrumentSpecificationDefinition {
    
    public static function setDefination($instrumentSpecification){
        return [
            'id' => $instrumentSpecification->id,
            'instrument_specification_name' => $instrumentSpecification->name
        ];
    }

}