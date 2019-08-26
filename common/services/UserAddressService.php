<?php

namespace common\services;

use Yii;
use common\models\useraddress\UserAddress;
use common\models\country\Country;

class UserAddressService {

    public static function getCountries() {
        return Country::find()->all();
    }

    public static function userAddress($type, $user_id = null, $address_id = null, $model = null, $form_data = null) {
        switch ($type) {
            case 'get':
                if ($address_id == null) {
                    $user_addresses = UserAddress::findAll(['user_id' => $user_id,'is_deleted' => 0]);
                }else if($address_id != null && $user_id != null) {
                    $user_addresses = UserAddress::find()->where(['user_id' => $user_id,'is_deleted' => 0,'id'=> $address_id])->one();
                } else {
                    $user_addresses = UserAddress::findOne($address_id);
                }
                return $user_addresses;

            case 'post':
                if ($form_data && $model) {
                    $model->attributes = $form_data;
                    $model->created_at = time();
                    $model->modified_at = time();
                    $model->user_id = $user_id;
                    $model->created_by = $user_id;
                    $model->modified_by = $user_id;
                    if ($model->default) {
                        UserAddress::updateAll(['default' => 0], ['user_id' => $user_id]);
                    }
                    if ($model->save()) {
                        return TRUE;
                    }
                }
                return FALSE;

            case 'put':
                if ($form_data && $model) {
                    $model->attributes = $form_data;
                    $model->modified_at = time();
                    $model->modified_by = $user_id;
                    if ($model->default) {
                        UserAddress::updateAll(['default' => 0], ['user_id' => $user_id]);
                    }
                    if ($model->update()) {
                        return TRUE;
                    }
                }
                return FALSE;

            case 'delete':
             if ($address_id == null) {
                    return $user_addresses =  UserAddress::updateAll(['is_deleted' => 1], ['user_id' => $user_id]);
                } else {
                    return $user_addresses =  UserAddress::updateAll(['is_deleted' => 1], ['id' => $address_id]);
                }

            default:
                return 'Invalid W/S method';
        }
    }

}
