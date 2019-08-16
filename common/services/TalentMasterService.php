<?php 

namespace common\services;

use Yii;
use common\models\industry2talentmaster\Industry2TalentMaster;
use common\models\industry2talentmaster\Industry2TalentMasterQuery;
use common\models\talentmaster\TalentMaster;
use common\models\talentmaster\TalentMasterQuery;


class TalentMasterService {

    public static function getTalentMasterRecordByIndustryId($id){
        return  Industry2TalentMaster::find()->where(['industry_id' => $id])->all();
    }

}

class TalentMasterDefinition {
    
    public static function setDefination($talentMaster){
        return [
            'id' => $talentMaster->id,
            'talent_name' => $talentMaster->name
        ];
    }

}