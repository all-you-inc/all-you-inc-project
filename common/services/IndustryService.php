<?php

namespace common\services;

use Yii;
use common\models\industry\Industry;
use common\models\industry\IndustryQuery;

class IndustryService {

    public static function getAll() {
        return Industry::find()->all();
    }

    public static function getAllIndusteryGenderFieldStatus() {
//        industry ids in array index
        $array = [];
        $array[1] = FALSE;
        $array[2] = TRUE;
        $array[3] = FALSE;
        $array[4] = TRUE;
        $array[5] = FALSE;
        $array[6] = FALSE;
        $array[7] = FALSE;
        $array[8] = TRUE;
        $array[9] = FALSE;
        $array[10] = FALSE;
        $array[11] = FALSE;
        $array[12] = FALSE;
        $array[13] = TRUE;
        $array[14] = TRUE;
        $array[15] = TRUE;
        $array[16] = FALSE;
        $array[17] = FALSE;
        $array[18] = FALSE;
        return $array;
    }

    public static function getAllMusicTalentGenderFieldStatus() {
//        talent ids in array index
//        0 for not show
//        1 for normal gender fields
//        2 for group gender fields
        $array = [];
        $array[50] = 0;
        $array[97] = 2;
        $array[98] = 2;
        $array[99] = 0;
        $array[100] = 0;
        $array[101] = 0;
        $array[102] = 1;
        $array[103] = 0;
        $array[104] = 0;
        $array[105] = 0;
        $array[106] = 0;
        $array[107] = 1;
        $array[108] = 2;
        $array[109] = 1;
        $array[110] = 1;
        $array[111] = 2;
        $array[112] = 0;
        $array[113] = 0;
        $array[114] = 0;
        $array[115] = 0;
        return $array;
    }

    public static function getIndusteryGenderFieldStatus($industry_id) {
        $gender_status = self::getAllIndusteryGenderFieldStatus();
        foreach ($gender_status as $id => $status) {
            if ($industry_id == $id && $status) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public static function getMusicTalentGenderFieldStatus($talent_id) {
        $gender_status = self::getAllMusicTalentGenderFieldStatus();
        foreach ($gender_status as $id => $status) {
            if ($talent_id == $id) {
                return $status;
            }
        }
        return FALSE;
    }

}

class IndustryDefinition {

    public static function setDefination($industry) {
        return [
            'id' => $industry->id,
            'industry_name' => $industry->name
        ];
    }

}

