<?php

namespace frontend\controllers\shop;

use shop\cart\Cart;
use shop\forms\Shop\Order\OrderForm;
use shop\useCases\Shop\OrderService;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\services\UserAddressService;
use common\services\UserPaymentService;
use common\services\UserMlmService;
use shop\services\TransactionManager;
use common\services\SquarePaymentService;
use common\models\userfunds\UserFunds;
use shop\repositories\UserRepository;

class CheckoutController extends Controller {

    private $transaction;
    public $layout = 'blank';
    private $service;
    private $cart;
    private $users;

    public function __construct($id, $module, OrderService $service, UserRepository $users, Cart $cart, TransactionManager $transaction, $config = []) {
        parent::__construct($id, $module, $config);
        $this->transaction = $transaction;
        $this->service = $service;
        $this->cart = $cart;
        $this->users = $users;
        }

        public function behaviors(): array {
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
                        $total_price = $product->price_new * $item->getQuantity();
                        $transaction_id = UserMlmService::createSalesOrderMlm($total_price, $product->id, Yii::$app->user->id);
                        if ($transaction_id) {
                            $funds = UserFunds::find()->where(['transaction_id' => $transaction_id])->all();
                            if ($funds) {
                                foreach ($funds as $fund) {
                                    if ($fund['amount'] != 0.00) {
                                        $this->users->sendEmail($fund, 'funds/user/fund-html', 'funds/user/fund-text', $fund->user->email, 'Your AllYouInc Wallet Transaction For Product');
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

    /**
     * @return mixed
     */
    public function actionIndex() {
        $form = new OrderForm($this->cart->getWeight());
        $session = \Yii::$app->session;
        $user_addresses = UserAddressService::userAddress('get', Yii::$app->user->id);
        if (isset(Yii::$app->request->post()['card_given']) && Yii::$app->request->post()['card_given'] == 1) {
            SquarePaymentService::$checkoutCard = 1;
            $form->customer->phone = $session['checkout_square']['phone'];
            $form->customer->name = $session['checkout_square']['name'];
            $form->delivery->method = $session['checkout_square']['method'];
            $form->delivery->index = $session['checkout_square']['index'];
            $form->delivery->address = $session['checkout_square']['address'];
            $form->note = $session['checkout_square']['note'];
        }
        if (!isset(Yii::$app->request->post()['card_given'])) {
            $form->load(Yii::$app->request->post());
        }
        if (Yii::$app->request->post()) {
            if ($form->validate()) {
                if ($this->cart->getItems() != null) {
                    try {
                        if (SquarePaymentService::$checkoutCard == 0) {
                            $session['checkout_square'] = [
                                'phone' => Yii::$app->request->post()['CustomerForm']['phone'],
                                'name' => Yii::$app->request->post()['CustomerForm']['name'],
                                'method' => Yii::$app->request->post()['DeliveryForm']['method'],
                                'index' => Yii::$app->request->post()['DeliveryForm']['index'],
                                'address' => Yii::$app->request->post()['DeliveryForm']['address'],
                                'note' => Yii::$app->request->post()['OrderForm']['note'],
                            ];
                            return $this->redirect(\yii\helpers\Url::base('https') . '/' . 'square/details?request=checkout');
                        }

                        $params = [];
                        $params['note'] = 'Purchase Products';
                        
                        $paymentGateway = UserPaymentService::paymentGateway($this->cart->getCost()->getTotal(),$params);
                        if ($paymentGateway['code'] == 200) {
                            $order = $this->service->checkout(Yii::$app->user->id, $form);
                            $payment = $this->actionPayment($order->id, $order['cost'], $this->cart, $paymentGateway);
                            $this->cart->clear();
                            if ($payment) {
                                $session->remove('checkout_square');
                                Yii::$app->session->setFlash('success', 'Your Order Has Been Placed');
                                return $this->redirect(['/cabinet/order/view', 'id' => $order->id]);
                            }
                        }
                        if (isset($paymentGateway['message'])) {
                            Yii::$app->session->setFlash('error', $paymentGateway['message']);
                        }
                    } catch (\DomainException $e) {
                        Yii::$app->errorHandler->logException($e);
                        Yii::$app->session->setFlash('error', $e->getMessage());
                    }
                }
            }
        }
        if ($this->cart->getItems() == null) {
            Yii::$app->session->setFlash('error', 'No product found');
        }
        SquarePaymentService::$checkoutCard = 0;
        return $this->render('index', [
                    'cart' => $this->cart,
                    'model' => $form,
                    'user_addresses' => $user_addresses,
        ]);
    }

}
