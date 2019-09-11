<?php
namespace api\controllers\user;

use Yii;
use yii\helpers\Url;
use yii\rest\Controller;
use api\helpers\DataHelper;
use api\helpers\DateHelper;
use shop\entities\User\User;
use common\services\UserMlmService;
use common\models\membership\MsItems;
use common\services\UserPaymentService;
use common\models\membership\Membership;
use common\services\SquarePaymentService;
use common\models\userreferral\UserReferral;
use common\models\usersquareinfo\UsersSquareInfo;
use common\models\usersubscription\UserSubscription;

class PurchaseController extends Controller
{

    public function actionPlan() {

        if (Yii::$app->request->post()) {
            $type = Yii::$app->request->getBodyParams()['type'];
            $nonce = Yii::$app->request->getBodyParams()['nonce'];
            $membership_id = Yii::$app->request->getBodyParams()['plan_id'];

            if($membership_id == null){
                return [
                    'code' => 400,
                    'data'=>[
                        'message'=> 'plan id is null'
                        ]
                    ];
            }

            $membershipCheck = Membership::findOne($membership_id);

            if( $membershipCheck == null ||
                $membershipCheck->status != 'active' ||
                $membershipCheck->is_deleted == 1){
                    return [
                        'code' => 400,
                        'data'=>[
                            'message'=> 'plan not found'
                            ]
                        ];
            }

            $user = \Yii::$app->user->identity->getUser();
            
            $cardDetails = [];
            if ($user->isUserCardNeedToAdd($membership_id)) {
                $cardDetails = $this->addCustomerWithCardDetails($nonce,$type);
            }
            else{
                $cardDetails['code'] = 200;
            }

            if($cardDetails['code'] == 200){ 
                
                $payment = $this->actionPayment($membership_id);

                if ($payment['code'] == 200) {
                    return [
                        'code' => 200,
                        'data'=>[
                            'message'=> $payment['message'],
                        ]
                    ];  
                }
                return [
                    'code' => 400,
                    'data'=>[
                        'message'=> $payment['message'],
                    ]
                ];
            }
            if(is_array($cardDetails['message'])){
                $errorMessage = '';
                foreach($cardDetails['message'] as $error){
                    $errorMessage .= $error; 
                }
                return [
                    'code' => 400,
                    'data'=>[
                        'message'=>$errorMessage
                        ]
                    ];  
            }
            return [
                'code' => 400,
                'data'=>[
                    'message'=>$cardDetails['message']
                    ],
                ];
        }
        return [
            'code' => 400,
            'data'=>[
                'message'=>'error : post request only with required params'
            ],
        ];
    }


    private function actionPayment($membership_id) {
        $membership = Membership::findOne($membership_id);
        if ($membership_id != Membership::FreeTalent && $membership_id != Membership::FreeTalentWithProduct && $membership_id != Membership::CustomerId && $membership_id != Membership::FanId) {
            $paymentGateway = UserPaymentService::paymentGateway($membership->price);
        } else {
            $paymentGateway = [];
            $paymentGateway['code'] = 200;
        }
        if ($paymentGateway['code'] == 200) {
            $user = \Yii::$app->user->identity->getUser();
            $user_subscription = UserPaymentService::createSubscription('membership', $membership_id, $user->id);
            if ($user_subscription && $membership_id != Membership::FreeTalent && $membership_id != Membership::FreeTalentWithProduct && $membership_id != Membership::CustomerId && $membership_id != Membership::FanId) {
                $params = [];
                $params['user_id'] = $user->id;
                $params['amount'] = $membership->price;
                $params['currency_id'] = $membership->currency_id;
                $params['type'] = 'subscription';
                $params['ref_id'] = $user_subscription->id;
                if (isset($paymentGateway['transection_id'])) {
                    $params['transection_id'] = $paymentGateway['transection_id'];
                    $params['status'] = 1;
                }
                $payment = UserPaymentService::createPayment($params);
                if ($payment) {
                    $user_referral = UserReferral::find()->where(['user_id' => $user->id])->one();
                    if ($user_referral) {
                        UserMlmService::createSignupMlm($user_referral->referral_code, $membership->price, $user->id);
                    }
                    return [
                        'code' => 200,
                        'message' => 'user subscribe successfully with payment',
                    ];
                }
            }
            return [
                'code' => 200,
                'message' => 'user subscribe successfully without payment',
            ];
        }
        return [
            'code' => 400,
            'message' => $paymentGateway['message'],
        ];
    }


    private function addCustomerWithCardDetails($nonce_code_sourceId, $type)
    {
        $nonce = $nonce_code_sourceId;
        
        if($nonce === null) {
            return [
                'code' => 400,
                'message' => 'Card Detail Not Found',
                ];
        }

        if($type != 'nonce' && $type != 'cardId') {
            return [
                'code' => 400,
                'message' => 'Request Type is Wrong',
                ];
        }

        $square = new SquarePaymentService;
        $square_Customer_id = '';
        
        $user = User::find()->where(['id' => \Yii::$app->user->id])->one();
        if($user->square_cust_id == null){
            // Create Customer In Square Payment Gateway
            $userDetails = [
                'email_address' => \Yii::$app->user->identity->getUser()->email,
                'given_name' => \Yii::$app->user->identity->getUser()->name,
                'reference_id' => strval(\Yii::$app->user->id),
                'note' => 'reference_id connected to user table with (user id : ' .\Yii::$app->user->id.')',
            ];
            $customerDetails = $square->createCustomer($userDetails);
            
            if(!is_object($customerDetails))
            {
                return [
                    'code' => 400,
                    'message' => $customerDetails,
                    ];
            }
            $errors = $customerDetails->getErrors();

            if($errors != null) {
                $errorArr = [];
                $i=0;
                foreach($errors as $error)
                {
                    $errorArr[$i] = $error->getDetails();
                    $i++;
                }  
                return [
                    'code' => 400,
                    'message' => $errorArr,
                    ];
            }

            $square_Customer_id = $customerDetails->getCustomer()->getId();
            $user->square_cust_id = $square_Customer_id;
            $user->save();
        }

        if($type == 'nonce')
        {

            $square_Customer_id = $user->square_cust_id;
            // Create Customer Card With Customer Id
            $customer_Card_Details = $square->addCustomerCardDetail($square_Customer_id,['card_nonce'=>$nonce]);
            
            if(!is_object($customer_Card_Details))
            {
                return [
                    'code' => 400,
                    'message' => $customer_Card_Details,
                    ];
            }

            $errors = $customer_Card_Details->getErrors();

            if($errors != null) {
                $errorArr = [];
                $i=0;
                foreach($errors as $error)
                {
                    $errorArr[$i] = $error->getDetails();
                    $i++;
                }  
                return [
                    'code' => 400,
                    'message' => $errorArr,
                    ];
            }

            $square_customer_card_id = $customer_Card_Details->getCard()->getId();

            $result = UsersSquareInfo::addNewCard($square_customer_card_id);
            
            if($result)
            {
                return [
                    'code' => 200,
                    'message' => 'Add Successfully',
                    ];
            }

            return [
                'code' => 400,
                'message' => 'Card not save in Allyouinc server',
            ];
        }
        else if($type == 'cardId'){
            $result = UsersSquareInfo::changeActiveCard($nonce);
            if($result){
                return [
                    'code' => 200,
                    'message' => 'Card Active Successfully',
                ]; 
            }
            return [
                'code' => 400,
                'message' => 'Card Not Found',
            ]; 
        
        }

        return [
            'code' => 400,
            'message' => 'Card Detail Not Found',
        ];
    }

}