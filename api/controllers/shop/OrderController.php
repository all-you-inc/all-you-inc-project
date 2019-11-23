<?php

namespace api\controllers\shop;

use shop\cart\Cart;
use shop\forms\Shop\Order\OrderForm;
use shop\useCases\Shop\OrderService;
use shop\readModels\Shop\OrderReadRepository;
use Yii;
use yii\helpers\Url;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use api\helpers\DataHelper;

class OrderController extends Controller
{
    private $cart;
    private $service;
    private $orders;

    public function __construct($id, $module,
                         OrderReadRepository $orders,
                         OrderService $service, 
                         Cart $cart,
                        $config = []
                        )
    {
        parent::__construct($id, $module, $config);
        $this->cart = $cart;
        $this->service = $service;
        $this->orders = $orders;
    }

    public function verbs(): array
    {
        return [
            'index' => ['POST'],
            'list' => ['GET']
        ];
    }

    public function actionIndex()
    {
        $form = new OrderForm($this->cart->getWeight());
        $form->load(Yii::$app->request->getBodyParams(), '');

        if ($form->validate()) {
            
            try {
                $order = $this->service->checkout(Yii::$app->user->id, $form);
                $response = Yii::$app->getResponse();
                $response->setStatusCode(204);
                return ['data'=>['completedAt'=>time()]];
            } catch (\DomainException $e) {
                throw new BadRequestHttpException($e->getMessage(), null, $e);
            }
        }

        return $form;
    }

    public function actionList(){

        $dataProvider = $this->orders->getOrdersFull(\Yii::$app->user->id);
        $result = [];

        foreach($dataProvider->getModels() as $model){
            array_push($result,$this->serializeListItem($model));
        }
       return ['data'=>$result];
 

    }

    public function serializeListItem($order): array
    {
        return [
            "cursor" => strval($order->id),
            "node" => [
                'id' => $order->id,
                'orderNumber' => $order->id,
                "processedAt" => date("Y-m-d h:i:s",$order->created_at),
                "deliveryMethod" => ['id'=>$order->delivery_method_id,'name'=>$order->delivery_method_name],
                "deliveryCost" => $order->delivery_cost,
                "currencyCode" => 'USD',
                "totalPrice" =>$order->cost,
                'cost' => $order->cost,
                "status" => $this->service->getStatusName($order->current_status),
                "customer" => ['name'=>$order->customer_name,'phone'=>$order->customer_phone],
                'address' => $order->delivery_address,
                'createdAt' => $order->created_at,
                "lineItems" => [
                    "pageInfo" => [  
                        "hasNextPage"=>false,
                        "hasPreviousPage"=>false
                    ],
                    "edges" => $this->dataItems($order)
                    
                ],
              
            ]
        ];
    }

    public function dataItems($order){
        $response = [];
        
        foreach($order->items as $key => $item){

            $result['id'] = $item->id;
            $result['orderNumber'] = $key;
            $result['title'] = $item->product_name;
            $result["price"] = $item->price;
            $result["totalPrice"] = $item->price;
            $result["processedAt"] = date("m-d-Y h:i:s",$order->created_at);
            $result["currencyCode"] = 'USD';
            $result["quantity"] = $item->quantity;
            
         
            array_push($response,$result);

        }
        return $response;
    }

}