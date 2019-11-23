<?php

namespace common\services;

use Yii;
use common\models\payment\Payment;
use common\models\country\Country;
use shop\entities\User\User;
use common\models\usersubscription\UserSubscription;
use common\models\membership\MsItems;
use common\models\membership\Membership;
use common\services\SquarePaymentService;

class UserPaymentService {

    public static function paymentGateway($amount, $params = []) {
        $responseArr = [];
        $square = new SquarePaymentService;
        $makeAmount = $amount * 100;
        $response = $square->payment($makeAmount,$params);
        $erros = $response->getErrors();
        if ($erros != null) {
            $responseArr['code'] = 400;
            $responseArr['message'] = '';
            foreach ($response->getErrors() as $error) {
                $responseArr['message'] += $error->getDetail();
            }
        } else {
            $responseArr['code'] = 200;
            $responseArr['transection_id'] = $response->getPayment()->getId();
            $responseArr['message'] = 'Payment Successfully';
        }
        return $responseArr;
    }

    public static function getAllSubscriptions($membership_id, $type = '') {

//        dd($membership_id);
        $items = [];
        if ($membership_id) {
            $condition = 'membership_id = ' . $membership_id;
            if ($type) {
                $condition .= ' AND type = "' . $type . '"';
            }
            $items = MsItems::find()->where($condition)->all();
//            dd($items);
        }
        return $items;
    }

    public static function createSubscription($type, $ref_id, $user_id, $group_id = '') {
        if ($group_id == '')
            $group_id = abs(crc32(uniqid()));

        $time = time();
        $model = new UserSubscription;
        
        if ($type == 'membership')
            $model->disablePrevious($user_id);
        
        $model->user_id = $user_id;
        $model->type = $type;
        $model->ref_id = $ref_id;
        $model->group_id = $group_id;
        $model->last_billing_date = $time;
        $model->status = 'active';
        $model->created_at = $time;
        $model->created_by = $user_id;
        $model->modified_at = $time;
        $model->modified_by = $user_id;
        if ($model->save()) {
            return $model;
        }
        return FALSE;
    }

    public static function createPayment($params) {
        $time = time();
        $model = new Payment();
        $model->attributes = $params;
        $model->created_at = $time;
        $model->modified_at = $time;
        $model->created_by = $params['user_id'];
        $model->modified_by = $params['user_id'];
        if ($model->save()) {
            return $model;
        }
        return FALSE;
    }

}
