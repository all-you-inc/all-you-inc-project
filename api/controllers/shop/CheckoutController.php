<?php

namespace api\controllers\shop;

use Yii;
use shop\cart\Cart;
use yii\helpers\Url;
use yii\rest\Controller;
use common\services\UserMlmService;
use shop\forms\Shop\Order\OrderForm;
use shop\useCases\Shop\OrderService;
use yii\web\BadRequestHttpException;
use common\services\UserPaymentService;
use common\services\SquarePaymentService;
use shop\entities\User\User;
use common\models\usersquareinfo\UsersSquareInfo;
use common\models\userfunds\UserFunds;
use shop\repositories\UserRepository;
use shop\services\TransactionManager;

class CheckoutController extends Controller
{
    private $cart;
    private $service;
    private $users;
    private $transaction;

    public function __construct($id, $module, OrderService $service, TransactionManager $transaction, UserRepository $users, Cart $cart, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->cart = $cart;
        $this->service = $service;
        $this->users = $users;
        $this->transaction = $transaction;
    }

    public function verbs(): array
    {
        return [
            'index' => ['POST'],
        ];
    }

    public function actionIndex()
    {
        $form = new OrderForm($this->cart->getWeight());
        $form->load(Yii::$app->request->getBodyParams(), '');
        $type = Yii::$app->request->getBodyParams()['type'];
        $nonce = Yii::$app->request->getBodyParams()['nonce'];
        $response = Yii::$app->getResponse();
        if ($form->validate()) {
            
            try {
                $cardDetails = $this->addCustomerWithCardDetails($nonce,$type);
                if($cardDetails['code'] == 200){ 
                    $paymentGateway = UserPaymentService::paymentGateway($this->cart->getCost()->getTotal());
                    if ($paymentGateway['code'] == 200) {
                        $order = $this->service->checkout(Yii::$app->user->id, $form);
                        $payment = $this->actionPayment($order->id, $order['cost'], $this->cart, $paymentGateway);
                        $this->cart->clear();
                        if ($payment) {
                            // $response->setStatusCode(204);
                            return ['data'=>['completedAt'=>time()]];
                        }
                        $response->setStatusCode(400);
                        return ['data'=>['message'=>$paymentGateway['message']]];
                    }
                    $response->setStatusCode(400);
                    return ['data'=>['message'=>$paymentGateway['message']]];
                }
                $response->setStatusCode(400);
                if(is_array($cardDetails['message'])){
                    $errorMessage = '';
                    foreach($cardDetails['message'] as $error){
                        $errorMessage .= $error; 
                    }
                    return ['data'=>['message'=>$errorMessage]];  
                }
                return ['data'=>['message'=>$cardDetails['message']]];    
            } catch (\DomainException $e) {
                throw new BadRequestHttpException($e->getMessage(), null, $e);
            }
        }

        return $form;
    }

    private function actionPayment($ref_id, $amount, $cart, $paymentGateway) {
        $params = [];
        $params['user_id'] = Yii::$app->user->id;
        $params['amount'] = $amount;
        $params['currency_id'] = 1;
        $params['type'] = 'sales_order';
        $params['ref_id'] = $ref_id;
        if (isset($paymentGateway['transection_id'])) {
            $params['transection_id'] = $paymentGateway['transection_id'];
            $params['status'] = 1;
        }
        $payment = UserPaymentService::createPayment($params);
        if ($payment) {
            $this->transaction->wrap(function () use ($cart) {
                if ($cart->getItems()) {
                    foreach ($cart->getItems() as $item) {
                        $product = $item->getProduct();
                        $transaction_id = UserMlmService::createSalesOrderMlm($product->price_new, $product->id, Yii::$app->user->id);
                        if ($transaction_id) {
                            $funds = UserFunds::find()->where(['transaction_id' => $transaction_id])->all();
                            if ($funds) {
                                foreach ($funds as $fund) {
                                    if ($fund['amount'] != 0.00) {
                                        $this->users->sendEmail($fund, 'funds/user/fund-html', 'funds/user/fund-text', $fund->user->email, 'Your AllYouInc Wallet Transection For Product');
                                    }
                                }
                            }
                        }
                    }
                }
            });
        }
        return TRUE;
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