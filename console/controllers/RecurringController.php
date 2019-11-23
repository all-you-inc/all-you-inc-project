<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use shop\entities\User\User;
use common\models\membership\Membership;
use common\models\membership\MsItems;
use common\models\usersubscription\UserSubscription;
use common\services\UserPaymentService;
use common\services\SquarePaymentService;
use shop\services\TransactionManager;

class RecurringController extends Controller
{
    private $transaction;
    const MEMBERSHIP = 'membership';
    const ADDONS     = 'addons';
    
    public function __construct($id, $module,TransactionManager $transaction, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->transaction = $transaction;
    }

    public function actionRecurringPayment()
    {
        $allUsers = User::find()->where(['status' => 10])->all();
        
        foreach($allUsers as $user)
        {
            $time = strtotime('-1 month');
            $membershipSubscription = UserSubscription::find()
                                ->where(['user_id' => $user->id, 'status' => 'active', 'type' => self::MEMBERSHIP])
                                ->andWhere(['<','last_billing_date',$time])
                                ->all();

            $addonsSubscription = UserSubscription::find()
                                ->select(['group_id'])
                                ->where(['user_id' => $user->id, 'status' => 'active', 'type' => self::ADDONS])
                                ->andWhere(['<','last_billing_date',$time])
                                ->groupBy(['group_id'])
                                ->all();
            
            if($membershipSubscription != NULL || $addonsSubscription != NULL)
            {
                $this->transaction->wrap(function () use ($membershipSubscription, $addonsSubscription, $user) 
                {

                    if($membershipSubscription != NULL)
                    {
                        foreach($membershipSubscription as $item)
                        {
                            $ref_id = $item->ref_id;
                            $billTime = $item->last_billing_date;
                            $chargingTime = strtotime('+1 month', $billTime);


                            if($ref_id == Membership::FreeTalent ||
                                    $ref_id == Membership::FreeTalentWithProduct)
                            {
                                $chargingTime = strtotime('+2 month', $billTime);
                                if(time() >= $chargingTime)
                                {
                                    if($ref_id == Membership::FreeTalent){
                                        $memberShipId = Membership::Talent;
                                    }else{
                                        $memberShipId = Membership::TalentWithProduct;
                                    }
                                    $PurchaseMemberShip = Membership::findOne($memberShipId);

                                    $this->paymentForMembership($memberShipId,'free', $PurchaseMemberShip->price, $user, $item);
                                }
                            }
                            else if($ref_id == Membership::Talent ||
                                $ref_id == Membership::TalentWithProduct ||
                                $ref_id == Membership::Promoter)
                            {
                                if(time() >= $chargingTime)
                                {
                                    $PurchaseMemberShip = Membership::findOne($ref_id);
                                    $this->paymentForMembership($ref_id,'paid', $PurchaseMemberShip->price, $user, $item);
                                }
                            }
                        }
                    }
                    
                    if($addonsSubscription != null)
                    {
                        foreach($addonsSubscription as $item)
                        {
                            $addons = UserSubscription::find()
                                    ->where(['user_id' => $user->id, 
                                            'status' => 'active',
                                            'type' => self::ADDONS, 
                                            'group_id' => $item->group_id])
                                    ->all();
                                
                            $billTime = $addons[0]->last_billing_date;
                            $chargingTime = strtotime('+1 month', $billTime);
                            
                            if(time() >= $chargingTime)
                            {
                                $totalPrice = 0;
                                foreach($addons as $addon)
                                {
                                    $addonDetail = MsItems::find()->where(['id' => $addon->ref_id])->one();
                                    $totalPrice +=  $addonDetail->price;
                                }
                                $this->paymentForAddons($item->group_id,$totalPrice,$user);
                            }
                        }
                    }
                });
            }
        }
        return;   
    }


    private function paymentForMembership($membership_id, $type, $price, $user , $subscription) 
    {
        $paymentGateway = $this->paymentGateway($price,$user);

        if ($paymentGateway['code'] == 200)
        {

            if ($type == 'free') 
            {
                UserSubscription::updateAll(['status' => 'in-active'], ['user_id' => $user->id, 'type' => 'membership']);
                $user_subscription = UserPaymentService::createSubscription('membership', $membership_id, $user->id);
            }
            else 
            {
                $user_subscription = $subscription;
                $user_subscription->last_billing_date = time();
                $user_subscription->modified_at = time();
                $user_subscription->save();
            }

            if($user_subscription)
            {
                $payment = $this->createPayment($user_subscription->id, $price, $user, $paymentGateway);
                return $payment;
            }
            return false;
        }
        else
        {
            $user_subscription = $subscription;
            $user_subscription->status = 'in-active';
            $user_subscription->modified_at = time();
            $user_subscription->save();
        }
    }

    private function paymentForAddons($addonGroupId, $price, $user) 
    {
        $paymentGateway = $this->paymentGateway($price,$user);

        if ($paymentGateway['code'] == 200)
        { 
            UserSubscription::updateAll(['last_billing_date' =>time(), 'modified_at' => time()], ['user_id' => $user->id, 'type' => self::ADDONS, 'group_id' => $addonGroupId]);
            $payment = $this->createPayment($addonGroupId, $price, $user, $paymentGateway);
            return $payment;
        }
        else
        {
            UserSubscription::updateAll(['status' => 'in-active'], ['user_id' => $user->id, 'type' => self::ADDONS , 'group_id' => $addonGroupId]);
        }
    }

    private function paymentGateway($amount, $user)
    {
        $responseArr = [];
        $square = new SquarePaymentService;
        $makeAmount = $amount * 100;
        $response = $square->consolePayment($makeAmount, $user);
        if($response !=  false)
        {  
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
        $responseArr['code'] = 400;
        $responseArr['message'] = 'Api Exception';
        return $responseArr;
    }


    private function createPayment($ref_id, $amount, $user, $paymentGateway) 
    {
        $params = [];
        $params['user_id'] = $user->id;
        $params['amount'] = $amount;
        $params['currency_id'] = 1;
        $params['type'] = 'subscription';
        $params['ref_id'] = $ref_id;
        if (isset($paymentGateway['transection_id'])) {
            $params['transection_id'] = $paymentGateway['transection_id'];
            $params['status'] = 1;
        }
        $payment = UserPaymentService::createPayment($params);
        if ($payment) {
            return true;
        }
        return false;
    }
}