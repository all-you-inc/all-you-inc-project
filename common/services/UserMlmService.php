<?php

namespace common\services;

use Yii;
use common\models\userreferral\UserReferral;
use shop\entities\User\User;
use common\models\tierspayout\TiersPayout;
use common\models\companyfunds\CompanyFunds;
use common\models\userfunds\UserFunds;
use common\models\promo\Promo;

class UserMlmService {

    const CompanyPercentInSignup = 60;
    const CompanyPercentInSalesOrder = 18;
    const OwnerProfit = 75;
    const ReferralsPercentInSignup = 40;
    const TransectionCost = 2.9;
    const TransectionFee = 0.3;

    public static function createModel($model, $params) {
        $model->attributes = $params;
        if ($model->save()) {
            return $model;
        }
        return FALSE;
    }

    public static function getPercentOfNumber($amount, $percent) {
        $result = ($percent / 100) * $amount;
        return $result;
    }

    public static function getReferralAmount($total_amount, $referral_user) {
        $tier_payout = TiersPayout::findOne($referral_user->tiers_payout_id);
        if ($tier_payout) {
            return self::getPercentOfNumber($total_amount, $tier_payout->percent);
        }
        return FALSE;
    }

    public static function getUserByReferralCode($ref_code) {
        return User::find()->where(['referral_code' => $ref_code])->one();
    }

    public static function getUserReferralByUserId($user_id) {
        return UserReferral::find()->where(['user_id' => $user_id])->one();
    }

    public static function getPromoByUserId($user_id, $product_id) {
        return Promo::find()->where(['user_id' => $user_id, 'type' => 'product', 'ref_id' => $product_id])->one();
    }

    public static function getReferralCode($user_id, $product_id) {
        $isPromoExist = self::getPromoByUserId($user_id, $product_id);
        $isReferralExist = self::getUserReferralByUserId($user_id);
        $ref_code = '';
        if ($isPromoExist) {
            $ref_code = $isReferralExist->referral_code;
        } elseif ($isReferralExist) {
            $ref_code = $isReferralExist->referral_code;
        }
        return $ref_code;
    }

    public static function createCompanyFund($referral_code, $type, $ref_id, $amount, $time, $transaction_id) {
        $params = [];
        $params['referral_code'] = $referral_code;
        $params['type'] = $type;
        $params['ref_id'] = $ref_id;
        $params['amount'] = $amount;
        $params['transaction_id'] = $transaction_id;
        $params['created_at'] = $time;
        $params['modified_at'] = $time;
        $params['created_by'] = $ref_id;
        $params['modified_by'] = $ref_id;
//        d($params);
        self::createModel(new CompanyFunds(), $params);
    }

    public static function createUserFund($user_id, $referral_code, $type, $ref_id, $amount, $time, $transaction_id) {
        $params = [];
        $params['user_id'] = $user_id;
        $params['referral_code'] = $referral_code;
        $params['type'] = $type;
        $params['ref_id'] = $ref_id;
        $params['amount'] = $amount;
        $params['transaction_id'] = $transaction_id;
        $params['created_at'] = $time;
        $params['modified_at'] = $time;
        $params['created_by'] = $ref_id;
        $params['modified_by'] = $ref_id;
        self::createModel(new UserFunds(), $params);
    }

    public static function createSignupMlm($ref_code, $amount, $user_id) {
        if ($ref_code && $amount && $user_id) {
            $transaction_id = Yii::$app->security->generateRandomString();
            $company_amount = self::getPercentOfNumber($amount, self::CompanyPercentInSignup);
            self::createCompanyFund($ref_code, 'user_signup', $user_id, $company_amount, time(), $transaction_id);
            $referals_amount = self::getPercentOfNumber($amount, self::ReferralsPercentInSignup);
            self::createTierRollingAmountWithReferral($ref_code, $referals_amount, $user_id, $transaction_id);
        }
        return FALSE;
    }

    public static function createSalesOrderMlm($amount, $product_id, $user_id) {
        if ($amount && $product_id && $user_id) {
            $ref_code = self::getReferralCode($user_id, $product_id);
            if ($ref_code != '') {
                $transaction_id = Yii::$app->security->generateRandomString();
                $transaction_cost = self::getPercentOfNumber($amount, self::TransectionCost);
                $transaction_fee = self::TransectionFee;
                $balance_amount = $amount - $transaction_cost - $transaction_fee;
                $company_amount = self::getPercentOfNumber($amount, self::CompanyPercentInSalesOrder);
                $balance_amount = $balance_amount - $company_amount;
                $owner_profit = self::getPercentOfNumber($balance_amount, self::OwnerProfit);
                $balance_amount = $balance_amount - $owner_profit;
                self::createCompanyFund($ref_code, 'product_sale', $product_id, $company_amount, time(), $transaction_id);
                self::createUserFund($user_id, $ref_code, 'product_sale', $product_id, $owner_profit, time(), $transaction_id);
                self::createTierRollingAmountWithReferral($ref_code, $balance_amount, $product_id, $transaction_id);
            }
        }
        return FALSE;
    }

    public static function createTierRollingAmountWithReferral($ref_code, $referals_amount, $ref_id, $transaction_id) {
        if ($ref_code && $referals_amount && $ref_id) {
            $balance = $referals_amount;
//For tier 1
            $r_user_1 = self::getUserByReferralCode($ref_code);
            if ($r_user_1) {
                $r_amount_1 = self::getReferralAmount($referals_amount, $r_user_1);
                if ($r_amount_1) {
                    self::createUserFund($r_user_1->id, $ref_code, 'user_signup', $ref_id, $r_amount_1, time(), $transaction_id);
                    $balance = $balance - $r_amount_1;
                }
//For tier 2            
                $referral_1 = self::getUserReferralByUserId($r_user_1->id);
                $r_user_2 = self::getUserByReferralCode($referral_1->referral_code);
                if ($r_user_2) {
                    $r_amount_2 = self::getReferralAmount($r_amount_1, $r_user_2);
                    if ($r_amount_2) {
                        self::createUserFund($r_user_2->id, $ref_code, 'user_signup', $ref_id, $r_amount_2, time(), $transaction_id);
                        $balance = $balance - $r_amount_2;
                    }
//For tier 3            
                    $referral_2 = self::getUserReferralByUserId($r_user_2->id);
                    $r_user_3 = self::getUserByReferralCode($referral_2->referral_code);
                    if ($r_user_3) {
                        $r_amount_3 = self::getReferralAmount($r_amount_2, $r_user_3);
                        if ($r_amount_3) {
                            self::createUserFund($r_user_3->id, $ref_code, 'user_signup', $ref_id, $r_amount_3, time(), $transaction_id);
                            $balance = $balance - $r_amount_3;
                        }
//For tier 4            
                        $referral_3 = self::getUserReferralByUserId($r_user_3->id);
                        $r_user_4 = self::getUserByReferralCode($referral_3->referral_code);
                        if ($r_user_4) {
                            $r_amount_4 = self::getReferralAmount($r_amount_3, $r_user_4);
                            if ($r_amount_4) {
                                self::createUserFund($r_user_4->id, $ref_code, 'user_signup', $ref_id, $r_amount_4, time(), $transaction_id);
                                $balance = $balance - $r_amount_4;
                            }
//For tier 5            
                            $referral_4 = self::getUserReferralByUserId($r_user_4->id);
                            $r_user_5 = self::getUserByReferralCode($referral_4->referral_code);
                            if ($r_user_5) {
                                $r_amount_5 = self::getReferralAmount($r_amount_4, $r_user_5);
                                if ($r_amount_5) {
                                    self::createUserFund($r_user_5->id, $ref_code, 'user_signup', $ref_id, $r_amount_5, time(), $transaction_id);
                                    $balance = $balance - $r_amount_5;
                                }
                            }
                        }
                    }
                }
            }
            self::createCompanyFund($ref_code, 'balance_amount', $ref_id, $balance, time(), $transaction_id);
            return TRUE;
        }
        return FALSE;
    }

}
