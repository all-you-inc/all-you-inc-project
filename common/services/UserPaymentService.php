<?php

namespace common\services;

use Yii;
use common\models\payment\Payment;
use common\models\country\Country;
use shop\entities\User\User;
use common\models\usersubscription\UserSubscription;

class UserPaymentService {

    public static function paymentGateway($params) {
        return TRUE;
    }

    public static function createSubscription($type, $ref_id, $user_id) {
        $time = time();
        $model = new UserSubscription;
        $model->user_id = $user_id;
        $model->type = $type;
        $model->ref_id = $ref_id;
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
