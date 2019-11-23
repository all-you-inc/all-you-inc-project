<?php

namespace common\services;

use Yii;
use common\models\userreferral\UserReferral;
use shop\entities\User\User;
use common\models\tierspayout\TiersPayout;
use common\models\companyfunds\CompanyFunds;
use common\models\userfunds\UserFunds;
use common\models\promo\Promo;
use shop\entities\Shop\Product\Product;
use shop\repositories\UserRepository;
use yii\data\Pagination;
use common\modules\customnotification\components\SignupComission;
use common\modules\customnotification\components\ProductPurchase;

class UserMlmService {

    const CompanyPercentInSignup = 60;
    const CompanyPercentInSalesOrder = 18;
    const OwnerProfit = 75;
    const ReferralsPercentInSignup = 40;
    const TransectionCost = 2.9;
    const TransectionFee = 0.3;
    const UserSignup = 'user_signup';
    const ProductSale = 'product_sale';
    const BalanceAmount = 'balance_amount';
    const EmailUsers = [];

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

    public static function serializedTree($model) {
        $result = [];
        foreach ($model as $item) {
            $result[$item->referralCodeUser->id][$item->user->id] = $item->user->name . ' ( ' . $item->user->email . ' ) -|+ ' . $item->user->referral_code;
        }
        return $result;
    }

    public static function getReferralCountAndLevel($user) {
        $tier_payout = TiersPayout::findOne($user->tiers_payout_id);
        $model = self::getUserReferralByReferralId($user->id);
        $result['tree'] = self::serializedTree($model);
        $result['count'] = count($model);
        $result['level'] = 'not set';
        if ($tier_payout) {
            $result['level'] = $tier_payout->title;
        }
        return $result;
    }

    public static function getUserByReferralCode($ref_code) {
        return User::find()->where(['referral_code' => $ref_code])->one();
    }

    public static function getUserById($user_id) {
        return User::findOne($user_id);
    }

    public static function getUserReferralByUserId($user_id) {
        return UserReferral::find()->where(['user_id' => $user_id])->one();
    }

    public static function getUserReferralByReferralId($id) {
        return UserReferral::find()->where(['referral_user_id' => $id])->orderBy(['tier' => SORT_ASC])->all();
    }

    public static function getPromoByUserId($user_id, $product_id) {
        return Promo::find()->where(['user_id' => $user_id, 'type' => 'product', 'ref_id' => $product_id])->one();
    }

    public static function getProductById($product_id) {
        return Product::findOne($product_id);
    }

    public static function getAllTiersReferralsByUserId($user_id) {
        return UserReferral::find()->where(['user_id' => $user_id])->orderBy(['tier' => SORT_ASC])->all();
    }

    public static function getReferralCode($user_id, $product_id) {
        $isPromoExist = self::getPromoByUserId($user_id, $product_id);
        $isReferralExist = self::getUserReferralByUserId($user_id);
        $ref_code = '';
        if ($isPromoExist) {
            $ref_code = $isPromoExist->referral_code;
        } elseif ($isReferralExist) {
            $ref_code = $isReferralExist->referral_code;
        }
        return $ref_code;
    }

    public static function getUserFunds($user_id, $pageSize = null, $referral_code = null) {
        if ($pageSize == null) {
            $pageSize = 10;
        }
        $dataProvider = UserFunds::find();
        $dataProvider = $dataProvider->where(['user_id' => $user_id]);
        if ($referral_code)
            $dataProvider = $dataProvider->andWhere(['referral_code' => $referral_code]);
        $dataProvider = $dataProvider->orderBy(['id' => SORT_DESC]);
        $countQuery = clone $dataProvider;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->defaultPageSize = $pageSize;
        $data = $dataProvider->all();
        $total = 0;
        if ($data) {
            foreach ($data as $item) {
                $total += $item->amount;
            }
        }
        $funds = $dataProvider->offset($pages->offset)->limit($pages->limit)->all();
        $results = [];
        if ($funds) {
            foreach ($funds as $fund) {
                $result = [];
                if ($fund->type == 'user_signup') {
                    $user = User::findOne($fund->ref_id);
                    $result['name'] = $user->name;
                } elseif ($fund->type == 'product_sale') {
                    $product = Product::findOne($fund->ref_id);
                    $result['name'] = $product->name;
                } else {
                    $result['name'] = '-';
                }
                $result['type'] = strtoupper(str_replace("_", " ", $fund->type));
                $result['id'] = $fund->ref_id;
                $result['amount'] = $fund->amount;
                $result['referral_code'] = $fund->referral_code;
                $result['referral_user'] = self::getUserByReferralCode($fund->referral_code)->name;
                $result['user_name'] = $fund->creater->name != NULL ? $fund->creater->name : 'Not Set';
                $result['transaction_id'] = $fund->transaction_id;
                $result['created_at'] = $fund->created_at;
                $results[] = $result;
            }
        }
        $data = [];
        $data['results'] = $results;
        $data['pages'] = $pages;
        $data['total'] = $total;
        return $data;
    }

    public static function getCompanyFunds() {
        $funds = CompanyFunds::find()->where(['is_deleted' => 0])->orderBy(['id' => SORT_DESC])->all();
        $results = [];
        if ($funds) {
            foreach ($funds as $fund) {
                $result = [];
                $result['type'] = strtoupper(str_replace("_", " ", $fund->type));
                $result['id'] = $fund->ref_id;
                $result['amount'] = $fund->amount;
                $result['referral_code'] = $fund->referral_code;
                $result['referral_user'] = self::getUserByReferralCode($fund->referral_code)->name;
                $result['transaction_id'] = $fund->transaction_id;
                $result['created_at'] = $fund->created_at;
                $results[] = $result;
            }
        }
        return $results;
    }

    public static function createCompanyFund($referral_code = '', $type, $ref_id, $amount, $time, $transaction_id) {
        $params = [];
        if ($referral_code != '') {
            $params['referral_code'] = $referral_code;
        }
        $params['type'] = $type;
        $params['ref_id'] = $ref_id;
        $params['amount'] = $amount;
        $params['transaction_id'] = $transaction_id;
        $params['created_at'] = $time;
        $params['modified_at'] = $time;
        $params['created_by'] = \Yii::$app->user->id;
        $params['modified_by'] = \Yii::$app->user->id;
        $company_funds = self::createModel(new CompanyFunds(), $params);
        if ($company_funds instanceof CompanyFunds) {
            \Yii::info('Successfully create company fund=> type:' . $type . ' ref_id:' . $ref_id . ' amount:' . $amount . ' transactionId:' . $transaction_id, 'mlm');
            return TRUE;
        }
        \Yii::info('Unsuccessfully create company fund=> type:' . $type . ' ref_id:' . $ref_id . ' amount:' . $amount . ' transactionId:' . $transaction_id, 'mlm');
        return FALSE;
    }

    public static function createUserFund($user_id, $referral_code, $type, $ref_id, $amount, $time, $transaction_id) {
        $params = [];
        $params['user_id'] = $user_id;
        if ($referral_code != '') {
            $params['referral_code'] = $referral_code;
        }
        $params['type'] = $type;
        $params['ref_id'] = $ref_id;
        $params['amount'] = $amount;
        $params['transaction_id'] = $transaction_id;
        $params['created_at'] = $time;
        $params['modified_at'] = $time;
        $params['created_by'] = \Yii::$app->user->id;
        $params['modified_by'] = \Yii::$app->user->id;
        $user_funds = self::createModel(new UserFunds(), $params);
        if ($user_funds instanceof UserFunds) {
            $from_user = User::findOne(\Yii::$app->user->id);
            $to_user = User::findOne($user_id);

            if ($type == self::UserSignup)
                SignupComission::instance()->from($from_user)->about($user_funds)->send($to_user);
            if ($type == self::ProductSale)
                ProductPurchase::instance()->from($from_user)->about($user_funds)->send($to_user);

            \Yii::info('Successfully create user fund=> type:' . $type . ' ref_id:' . $ref_id . ' user_id:' . $user_id . ' amount:' . $amount . ' transactionId:' . $transaction_id, 'mlm');
            return TRUE;
        }
        \Yii::info('Unsuccessfully create user fund=> type:' . $type . ' ref_id:' . $ref_id . ' user_id:' . $user_id . ' amount:' . $amount . ' transactionId:' . $transaction_id, 'mlm');
        return FALSE;
    }

    public static function createSignupMlm($ref_code = '', $amount, $user_id) {
        if ($amount && $user_id) {
            $time = time();
            $transaction_id = Yii::$app->security->generateRandomString();
            $transaction_cost = self::getPercentOfNumber($amount, self::TransectionCost);
            $transaction_fee = self::TransectionFee;
            $balance_amount = $amount - $transaction_cost - $transaction_fee;
            if ($ref_code == '') {
                self::createCompanyFund('', self::UserSignup, $user_id, $balance_amount, $time, $transaction_id);
            } elseif ($ref_code != '') {
                $company_amount = self::getPercentOfNumber($amount, self::CompanyPercentInSignup);
                self::createCompanyFund($ref_code, self::UserSignup, $user_id, $company_amount, $time, $transaction_id);
                $referals_amount = self::getPercentOfNumber($balance_amount, self::ReferralsPercentInSignup);
                self::createTierRollingAmountWithReferral($ref_code, $referals_amount, $user_id, $transaction_id, self::UserSignup, $time);
            }
            return $transaction_id;
        }
        return FALSE;
    }

    public static function createSalesOrderMlm($amount, $product_id, $user_id) {
        if ($amount && $product_id && $user_id) {
            $time = time();
            $transaction_id = Yii::$app->security->generateRandomString();
            $transaction_cost = self::getPercentOfNumber($amount, self::TransectionCost);
            $transaction_fee = self::TransectionFee;
            $balance_amount = $amount - $transaction_cost - $transaction_fee;
            $ref_code = self::getReferralCode($user_id, $product_id);
            $company_amount = self::getPercentOfNumber($amount, self::CompanyPercentInSalesOrder);
            $balance_amount = $balance_amount - $company_amount;
            $owner_profit = self::getPercentOfNumber($balance_amount, self::OwnerProfit);
            $balance_amount = $balance_amount - $owner_profit;
            $product = self::getProductById($product_id);
            self::createCompanyFund($ref_code, self::ProductSale, $product_id, $company_amount, $time, $transaction_id);
            self::createUserFund($product->created_by, $ref_code, self::ProductSale, $product_id, $owner_profit, $time, $transaction_id);
            if ($ref_code != '') {
                self::createTierRollingAmountWithReferral($ref_code, $balance_amount, $product_id, $transaction_id, self::ProductSale, $time);
            }
            return $transaction_id;
        }
        return FALSE;
    }

    public static function createTierRollingAmountWithReferral($ref_code, $referals_amount, $ref_id, $transaction_id, $type, $time) {
        if ($ref_code && $referals_amount && $ref_id) {
            $balance = $referals_amount;
//For Referral User
            $referral_user = self::getUserByReferralCode($ref_code);
            if ($referral_user instanceof User) {
                $referral_amount = self::getReferralAmount($balance, $referral_user);
                if ($referral_amount) {
                    $user_fund = self::createUserFund($referral_user->id, $ref_code, $type, $ref_id, $referral_amount, $time, $transaction_id);
                    if ($user_fund)
                        $balance = $balance - $referral_amount;
                }
//For All Tires Users
                $referrals = self::getAllTiersReferralsByUserId($referral_user);
                if ($referrals) {
                    foreach ($referrals as $referral) {
                        $referral_user = self::getUserById($referral->referral_user_id);
                        $referral_amount = self::getReferralAmount($balance, $referral_user);
                        if ($referral_user instanceof User) {
                            if ($referral_amount)
                                $user_fund = self::createUserFund($referral_user->id, $ref_code, $type, $ref_id, $referral_amount, $time, $transaction_id);
                            if ($user_fund)
                                $balance = $balance - $referral_amount;
                        }
                    }
                }
            }
            self::createCompanyFund($ref_code, self::BalanceAmount, $ref_id, $balance, $time, $transaction_id);
            return TRUE;
        }
        return FALSE;
    }

}
