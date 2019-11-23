<?php

namespace common\services;

use Yii;
use yii\web\Session;
use common\models\userreferral\UserReferral;
use shop\entities\User\User;
use common\models\tierspayout\TiersPayout;
use common\models\promo\Promo;

class UserReferralService {

    const Tier2 = 2;
    const Tier3 = 3;
    const Tier4 = 4;
    const Tier5 = 5;
    const Tier2Limit = 500; //up to 500
    const Tier3Limit = 250; //up to 250
    const Tier4Limit = 100; //up to 100
    const Tier5Limit = 50; //up to 50

    public static function createUserReferral($user_id, $referral_id, $referral_code, $tier, $time) {
        if (self::checkUserReferralTierCount($referral_id, $tier)) {
            $params = [];
            $params['user_id'] = $user_id;
            $params['referral_user_id'] = $referral_id;
            $params['referral_code'] = $referral_code;
            $params['tier'] = $tier;
            $params['created_at'] = $time;
            $params['modified_at'] = $time;
            $params['created_by'] = $user_id;
            $params['modified_by'] = $user_id;
            $model = new UserReferral();
            $model->attributes = $params;
            if ($model->save()) {
                self::checkAndUpdateReferralTierPayoutId($referral_id);
                return $model;
            }
        }
        return FALSE;
    }

    public static function checkUserReferralTierCount($referral_id, $tier) {
        $count = UserReferral::find()->where(['referral_user_id' => $referral_id, 'tier' => $tier])->count();
        if ($tier = self::Tier2 && $count > self::Tier2Limit)
            return FALSE;
        elseif ($tier = self::Tier3 && $count > self::Tier3Limit)
            return FALSE;
        elseif ($tier = self::Tier4 && $count > self::Tier4Limit)
            return FALSE;
        elseif ($tier = self::Tier5 && $count > self::Tier5Limit)
            return FALSE;
        return TRUE;
    }

    public static function checkAndUpdateReferralTierPayoutId($referral_id) {
        $count = UserReferral::find()->where(['referral_user_id' => $referral_id])->count();
        $tiers = TiersPayout::find()->all();
        $tier_id = 0;
        foreach ($tiers as $tier) {
            $team = explode("-", $tier->team);
            if (($team[0] <= $count) && ($count <= $team[1])) {
                $tier_id = $tier->id;
                break;
            }
        }
        $referral_user = User::findOne($referral_id);
        if ($tier_id != 0) {
            $referral_user->tiers_payout_id = $tier_id;
            $referral_user->update();
        }
        return FALSE;
    }

    public static function addReferralInSession($ref_code, $product_id) {
        $session = Yii::$app->session;
        $session['referral'] = [
            'id' => $product_id,
            'code' => $ref_code,
            'type' => 'product',
        ];
        self::checkAndCreatePromoFromSession();
    }

    public static function checkAndCreatePromoFromSession() {
        $session = Yii::$app->session;
        if ($session['referral'] && !Yii::$app->user->isGuest) {
            $params = $session['referral'];
            $user_id = Yii::$app->user->id;
            $isExist = Promo::find()->where(['user_id' => $user_id, 'ref_id' => $params['id'], 'type' => $params['type']])->one();
            if (!$isExist) {
                $time = time();
                $model = new Promo();
                $model->user_id = $user_id;
                $model->ref_id = $params['id'];
                $model->type = $params['type'];
                $model->referral_code = $params['code'];
                $model->created_at = $time;
                $model->modified_at = $time;
                $model->created_by = $user_id;
                $model->modified_by = $user_id;
                if ($model->save()) {
                    $session->remove('referral');
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public static function checkAndCreateReferralFromSession() {
        $session = Yii::$app->session;
        if ($session['referral'] && !Yii::$app->user->isGuest) {
            $is_exist = self::getUserReferralByUserId(Yii::$app->user->id);
            if (!$is_exist) {
                $referral = self::getUserByReferralCode($session['referral']['code']);
                self::createAllTierReferrals(Yii::$app->user->id, $referral->id, $session['referral']['code']);
                $session->remove('referral');
            } else {
                self::checkAndCreatePromoFromSession();
            }
        }
    }

    public static function createAllTierReferrals($user_id, $referral_user_id, $ref_code) {
//For tier 2
        self::createUserReferral($user_id, $referral_user_id, $ref_code, self::Tier2, time());
        $referral_2 = UserReferral::find()->where(['user_id' => $referral_user_id])->one();
        if ($referral_2) {
//For tier 3
            self::createUserReferral($user_id, $referral_2->referral_user_id, $ref_code, self::Tier3, time());
            $referral_3 = UserReferral::find()->where(['user_id' => $referral_2->referral_user_id])->one();
            if ($referral_3) {
//For tier 4
                self::createUserReferral($user_id, $referral_3->referral_user_id, $ref_code, self::Tier4, time());
                $referral_4 = UserReferral::find()->where(['user_id' => $referral_3->referral_user_id])->one();
                if ($referral_4) {
//For tier 5
                    self::createUserReferral($user_id, $referral_4->referral_user_id, $ref_code, self::Tier5, time());
                }
            }
        }
//        dd('end');
    }

    public static function getUserReferralByUserId($user_id) {
        return UserReferral::find()->where(['user_id' => $user_id])->one();
    }

    public static function getUserByReferralCode($ref_code) {
        return User::find()->where(['referral_code' => $ref_code])->one();
    }

    public static function getUserByEmail($email) {
        return User::find()->where(['email' => $email])->one();
    }

    public static function createReferrals($ref_code, $email) {
        if ($ref_code && $email) {
            $referral = self::getUserByReferralCode($ref_code);
            $user = self::getUserByEmail($email);
            if ($referral && $user) {
                self::createAllTierReferrals($user->id, $referral->id, $ref_code);
                return TRUE;
            }
        }
        return FALSE;
    }

}
