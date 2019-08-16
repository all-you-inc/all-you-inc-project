<?php

namespace frontend\controllers\shop;

use shop\cart\Cart;
use shop\forms\Shop\Order\OrderForm;
use shop\useCases\Shop\OrderService;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\services\UserAddressService;

class CheckoutController extends Controller {

    public $layout = 'blank';
    private $service;
    private $cart;

    public function __construct($id, $module, OrderService $service, Cart $cart, $config = []) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->cart = $cart;
    }

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

/**
 * @return mixed
 */
public function actionIndex() {
    $form = new OrderForm($this->cart->getWeight());
    $user_addresses = UserAddressService::userAddress('get', Yii::$app->user->id);
//dd($user_addresses);
    if ($form->load(Yii::$app->request->post()) && $form->validate()) {
        try {
            $order = $this->service->checkout(Yii::$app->user->id, $form);
            Yii::$app->session->setFlash('success', 'Your Order Has Been Placed');
            return $this->redirect(['/cabinet/order/view', 'id' => $order->id]);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

    return $this->render('index', [
                'cart' => $this->cart,
                'model' => $form,
                'user_addresses' => $user_addresses,
    ]);
}

}
