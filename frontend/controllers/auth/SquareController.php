<?php

namespace frontend\controllers\auth;

use Yii;
use yii\web\Controller;
use shop\cart\Cart;
use shop\entities\User\User;
use yii\filters\AccessControl;
use common\services\UserPaymentService;
use common\models\membership\Membership;
use common\services\SquarePaymentService;
use common\models\usersquareinfo\UsersSquareInfo;
use common\models\membership\MsItems;

class SquareController extends Controller 
{
    public $layout = 'main';
    private $cart;
    // Auth User Access Only
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function __construct($id, $module, Cart $cart, $config = []) {
        parent::__construct($id, $module, $config);
        $this->cart = $cart;
    }

    public function actionPaymentForm()
    {
        $response = [];
        $session = \Yii::$app->session;
        $allCards = $this->getAllCards();
        // 1- membership, 2- addons, 3- checkout
        $request = Yii::$app->request->getQueryParam('request');

        // ONLY FOR MEMBERSHIP
        if($request == 'membership'){
            $response['request'] = $request;
            $membershipId = $session['membership_id'];

            if($membershipId == null){
                return $this->goHome();
            }
            
            // title , price
            $membership = Membership::find()->where(['status' => 'active', 'is_deleted' => 0, 'id' => $membershipId])->one();
            $response['membership'] = $membership;
            
            $type = null;
            if($membershipId == Membership::Talent || $membershipId == Membership::TalentWithProduct){
                $type = 'basic';
            }else if($membershipId == Membership::FreeTalent || $membershipId == Membership::FreeTalentWithProduct){
                $type = 'free';
            }
            $response['msItems'] = UserPaymentService::getAllSubscriptions($membershipId,$type);
        }
        else if($request == 'checkout'){
            $response['request'] = $request;
            $response['cart'] = $this->cart;
            if($response['cart']->getItems() == null){
                return $this->goHome();
            }
        }
        else if($request == 'addons'){
            $totalPrice = 0;
            $response['request'] = $request;
            $sessionData = $session['addons'];

            if($sessionData == null){
                return $this->goHome();
            }

            if($sessionData['basic'] != null){
                $type = null;
                if($sessionData['basic'] == Membership::Talent || $sessionData['basic'] == Membership::TalentWithProduct){
                    $type = 'basic';
                }
                $membership = Membership::find()->where(['status' => 'active', 'is_deleted' => 0, 'id' => $sessionData['basic']])->one();
                $response['membership'] = $membership;
                $totalPrice += $membership->price;
                $response['membershipItems'] = UserPaymentService::getAllSubscriptions($sessionData['basic'],$type);
            }

            if($sessionData['addons'] != null){
                $response['addons'] = [];
                foreach($sessionData['addons'] as $addonsId){
                    $singleItem = MsItems::find()->where(['id' => $addonsId])->one();
                    $totalPrice += $singleItem->price;
                    array_push($response['addons'],$singleItem);
                }
                $response['addonsTotalPrice'] = $totalPrice;
            }
            $session['addonsTotalPrice'] = $totalPrice;
        }

        if($request == null) {
            return $this->goHome();
        }
        return $this->render('payment',['response'=>$response,'allcards' => $allCards]);
    }


    public function actionAddCustomerWithCardDetails()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $nonce = Yii::$app->request->post()['nonce'];

        if($nonce === null){
            return [
                'code' => 400,
                'message' => 'Card Detail Not Found',
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
                'note' => 'reference_id connected to user table with (user id' .\Yii::$app->user->id.')',
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

            if($errors != null){
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

        if($errors != null){
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

    public function getAllCards()
    {
        $cards = UsersSquareInfo::find()->where(['user_id' => \Yii::$app->user->id])->all();
        if($cards != null)
        {
            if(\Yii::$app->user->identity->getUser()->square_cust_id != null){
                $square = new SquarePaymentService;
                $result = $square->retrieveCustomer(\Yii::$app->user->identity->getUser()->square_cust_id);
                if(!is_object($result))
                {
                    return [
                        'code' => 400,
                        'message' => $result,
                        ];
                }
                $errors = $result->getErrors();

                if($errors != null){
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
                $customer = $result->getCustomer();
                $cards = $customer->getCards();
                $cardsArr = [];
                $customerArr = [
                    'id' => $customer->getId(),
                    'name' => $customer->getGivenName(),
                    'email' => $customer->getEmailAddress(),
                    'refId' => $customer->getReferenceId(),
                    
                ];
                $i=0;
                foreach($cards as $card){
                    $makeImageName = '';

                    if($card->getCardBrand() == 'VISA'){
                        $makeImageName = 'visa.png';
                    }else if($card->getCardBrand() == 'MASTERCARD'){
                        $makeImageName = 'master.png';
                    }else if($card->getCardBrand() == 'DISCOVER'){
                        $makeImageName = 'discover.png';
                    }else if($card->getCardBrand() == 'DISCOVER_DINERS'){
                        $makeImageName = 'diners_club.png';
                    }else if($card->getCardBrand() == 'JCB'){
                        $makeImageName = 'jcb.png';
                    }else if($card->getCardBrand() == 'AMERICAN_EXPRESS'){
                        $makeImageName = 'americanexp.png';
                    }else if($card->getCardBrand() == 'CHINA_UNIONPAY'){
                        $makeImageName = 'cup.png';
                    }else{
                        $makeImageName = 'democard.png';
                    }

                    $cardsArr[$i] = [
                        'sourceId' => $card->getId(),
                        'cardBrand' => $card->getCardBrand(),
                        'last4Digit' => $card->getLast4(),
                        'expMonth' => $card->getExpMonth(),
                        'expYear' => $card->getExpYear(),
                        'imageLink' => \Yii::$app->params['staticHostInfo'] . '/' . 'cards' . '/' . $makeImageName,
                    ];
                    $i++;
                }

                return [
                        'code' => 200,
                        'message' => 'Card Found',
                        'data' => [
                            'customerDetail' => $customerArr,
                            'customerCards' => $cardsArr
                        ],
                    ];
            }
            return [
                'code' => 400,
                'message' => 'Square Id Not Found',
                ];
        }
        return [
            'code' => 201,
            'message' => 'No Card Found',
        ];
    }

    public function actionChangeActiveCard(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(\Yii::$app->request->post()['type'] == 'changer'){
            if(\Yii::$app->request->post()['id'] != null){
                $result = UsersSquareInfo::changeActiveCard(\Yii::$app->request->post()['id']);
                if($result){
                    return [
                        'code' => 200,
                        'message' => 'Card Active Successfully',
                        'data' => [
                            'sourceId' => \Yii::$app->request->post()['id'],
                        ]
                    ]; 
                }
                return [
                    'code' => 400,
                    'message' => 'Card Not Found',
                ]; 
            }
            return [
                'code' => 400,
                'message' => 'No Card Is Selected',
            ]; 
        }
        return [
            'code' => 400,
            'message' => 'Error Somthing Wrong',
        ];
    }
}
