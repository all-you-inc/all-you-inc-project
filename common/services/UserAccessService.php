<?php

namespace common\services;

use Yii;
use common\models\payment\Payment;
use common\models\country\Country;
use shop\entities\User\User;
use common\models\usersubscription\UserSubscription;
use common\models\membership\MsItems;
use common\models\usertalent\UserTalent;
use shop\entities\Shop\Product\Product;

class UserAccessService {

    public static function checkByKey($user, $key) {
        $type = $user->getPaymentType();
        $membership = UserSubscription::find()->where(['user_id' => $user->id, 'type' => 'membership', 'status' => 'active'])->one();
        $ms_items = MsItems::find()->where(['membership_id' => $membership->ref_id, 'type' => $type])->all();
        $addons = UserSubscription::find()->where(['user_id' => $user->id, 'type' => 'addons', 'status' => 'active'])->all();
        $addons_items = [];
        if ($addons) {
            $ids = '';
            foreach ($addons as $addon) {
                $ids .= $addon->ref_id . ',';
            }
            $ids = rtrim($ids, ',');
            $addons_items = MsItems::find()->where('id IN (' . $ids . ')')->all();
        }
        $items = array_merge($ms_items, $addons_items);
        $result = [];
        if ($items) {
            foreach ($items as $item) {
                if ($item->itemType->key == $key) {
                    $result[] = $item;
                }
            }
        }
        return $result;
    }

    public static function checkLimitByMsItem($user_id, $items, $key) {

        switch ($key) {
            case 'TALENT':
                $count = UserTalent::find()->where(['user_id' => $user_id])->count();
                $items_unit = 0;
                foreach($items as $item){
                    $items_unit += $item->unit;
                }
                if ($items_unit > $count) {
                    return TRUE;
                }
                return FALSE;
            case 'PRODUCTS':
                $count = Product::find()->where(['created_by' => $user_id])->count();
                $items_unit = 0;
                foreach($items as $item){
                    $items_unit += $item->unit;
                }
                if ($items_unit > $count) {
                    return TRUE;
                }
                return FALSE;
            default:
                return FALSE;
        }
    }

}
