<?php

namespace common\services;

use Yii;
use common\models\useraddress\UserAddress;
use common\models\country\Country;

class UserAddressService {

    public static function getCountries() {
        return Country::find()->all();
    }

    public static function getCountry4mIso($code){
        return Country::find()->where(["iso_code_2"=>$code])->one();
    }

    public static function getCountryFrmId($id){
        return Country::find()->where(["id"=>$id])->one();
    }


    public static function userAddress($type, $user_id = null, $address_id = null, $model = null, $form_data = null) {
        switch ($type) {
            case 'get':
                if ($address_id == null) {
                    $user_addresses = UserAddress::findAll(['user_id' => $user_id, 'is_deleted' => 0]);
                } else if ($address_id != null && $user_id != null) {
                    $user_addresses = UserAddress::find()->where(['user_id' => $user_id, 'is_deleted' => 0, 'id' => $address_id])->one();
                } else {
                    $user_addresses = UserAddress::findOne($address_id);
                }
                return $user_addresses;

            case 'post':
                if ($form_data && $model) {
                    $model->attributes = $form_data;

                    $country = self::getCountry4mIso($form_data['country_id']);
                    
                    if ($country) {
                        $model->country_id = $country->id;
                    }
                    
                    self::updateLatitudeLongitude($model);
                    $model->created_at = time();
                    $model->modified_at = time();
                    $model->user_id = $user_id;
                    $model->created_by = $user_id;
                    $model->modified_by = $user_id;
                    if ($model->default) {
                        UserAddress::updateAll(['default' => 0], ['user_id' => $user_id]);
                    }
                    if ($model->save()) {
                        return $model;
                    }
                }
                return FALSE;

            case 'put':
                if ($form_data && $model) {
                    $model->attributes = $form_data;
                    $country = self::getCountry4mIso($form_data['country_id']);

                    if ($country) {
                        $model->country_id = $country->id;
                    }

                    self::updateLatitudeLongitude($model);
                    $model->modified_at = time();
                    $model->modified_by = $user_id;
                    if ($model->default) {
                        UserAddress::updateAll(['default' => 0], ['user_id' => $user_id]);
                    }
                    if ($model->update()) {
                        return $model;
                    }
                }
                return FALSE;

            case 'delete':
                if ($address_id == null) {
                    return $user_addresses = UserAddress::updateAll(['is_deleted' => 1], ['user_id' => $user_id]);
                } else {
                    return $user_addresses = UserAddress::updateAll(['is_deleted' => 1], ['id' => $address_id]);
                }

            default:
                return 'Invalid W/S method';
        }
    }
    
    private static function updateLatitudeLongitude(UserAddress $model) {
        if ($model->address) {
            $model->latitude = NULL;
            $model->longitude = NULL;
            $country = UserAddressService::getCountryFrmId($model->country_id);
            
            $results = self::geocode($model->address, $country->title);

            if ($results) {
                $model->latitude = $results['latitude'];
                $model->longitude = $results['longitude'];
            }
        }
    }
    
    private static function geocode($address, $country) {
        try {
            $geocode_url = 'https://maps.googleapis.com/maps/api/geocode/json' .
                    '?key=AIzaSyB2KpOwC1rO2RAvR08pPA5kapTIZcPHC7c' .
                    '&address=' . urlencode($address);

            if ($country) {
                $geocode_url .= '&components=country:' . urlencode($country);
            }
            
            $response_json = file_get_contents($geocode_url);
            $response = json_decode($response_json, TRUE);
            
            if ($response['status'] === 'OK') {
                return [
                    'latitude' => strval($response['results'][0]['geometry']['location']['lat']),
                    'longitude' => strval($response['results'][0]['geometry']['location']['lng'])
                ];
            } else {
//                d($response['status']);
            }
        } catch (\yii\base\ErrorException $ex) {
//            d($ex);
        }
        
        return FALSE;
    }
}
