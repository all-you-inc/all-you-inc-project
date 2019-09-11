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
        // first purchase member ship
        $membershipId = Yii::$app->request->getQueryParam('id');
        // 1- membership, 2- addons, 3- checkout
        $request = Yii::$app->request->getQueryParam('request');

        // ONLY FOR MEMBERSHIP
        if($request == 'membership'){
            $response['request'] = $request;
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
            $response['request'] = $request;
            
        }


        return $this->render('payment',['response'=>$response]);
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

    public function actionGetAllCards()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(\Yii::$app->request->post()['type'] == 'model'){
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
                        $cardsArr[$i] = [
                            'sourceId' => $card->getId(),
                            'cardBrand' => $card->getCardBrand(),
                            'last4Digit' => $card->getLast4(),
                            'expMonth' => $card->getExpMonth(),
                            'expYear' => $card->getExpYear(),
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
                'code' => 400,
                'message' => 'No Card Found',
            ];
        }
        return [
            'code' => 400,
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
