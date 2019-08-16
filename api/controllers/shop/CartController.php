<?php

namespace api\controllers\shop;

use shop\cart\CartItem;
use shop\cart\cost\Discount;
use shop\forms\Shop\AddToCartForm;
use shop\readModels\Shop\ProductReadRepository;
use shop\useCases\Shop\CartService;
use Yii;
use yii\helpers\Url;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use shop\cart\Cart;
use api\controllers\shop\ProductController;
use api\helpers\DataHelper;

class CartController extends Controller
{
    private $products;
    private $service;

    public function __construct($id, $module, CartService $service, ProductReadRepository $products, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->products = $products;
        $this->service = $service;
    }

    public function verbs(): array
    {
        return [
            'index' => ['GET'],
            'add' => ['POST'],
            'quantity' => ['PUT'],
            'delete' => ['DELETE'],
            'clear' => ['DELETE'],
        ];
    }

  

    /**
     * @SWG\Get(
     *     path="/shop/cart",
     *     tags={"Cart"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/Cart"),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */
    public function actionIndex(): array
    {
        $cart = $this->service->getCart();
        $cost = $cart->getCost();

        return DataHelper::serializeCart($cart,$cost);
      
    }

    /**
     * @SWG\Post(
     *     path="/shop/products/{productId}/cart",
     *     tags={"Cart"},
     *     @SWG\Parameter(name="productId", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="modification", in="formData", required=false, type="integer"),
     *     @SWG\Parameter(name="quantity", in="formData", required=true, type="integer"),
     *     @SWG\Response(
     *         response=201,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @param $id
     * @return array|AddToCartForm
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionAdd($id)
    {
        if (!$product = $this->products->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $form = new AddToCartForm($product);
        $form->load(Yii::$app->request->getBodyParams(), '');

        if ($form->validate()) {
            try {
                $this->service->add($product->id, $form->modification, $form->quantity);
                Yii::$app->getResponse()->setStatusCode(201);
                $cart = $this->service->getCart();
                $cost = $cart->getCost();
                return ['data'=>
                            ['checkoutLineItemsAdd'=>DataHelper::serializeCart($cart,$cost)]
                    ];
            } catch (\DomainException $e) {
                throw new BadRequestHttpException($e->getMessage(), null, $e);
            }
        }

        return ['errors' => $form->getErrors()];
    }

    /**
     * @SWG\Put(
     *     path="/shop/cart/{id}/quantity",
     *     tags={"Cart"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="string"),
     *     @SWG\Parameter(name="quantity", in="formData", required=true, type="integer"),
     *     @SWG\Response(
     *         response=201,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @param $id
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionQuantity($id)
    {
        try {
            $this->service->set($id, (int)Yii::$app->request->post('quantity'));
            $cart = $this->service->getCart();
            $cost = $cart->getCost();
            return ['data'=>
                            ['checkoutLineItemsUpdate'=>DataHelper::serializeCart($cart,$cost)]
                ];

        } catch (\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage(), null, $e);
        }
    }

    /**
     * @SWG\Delete(
     *     path="/shop/cart/{id}",
     *     tags={"Cart"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="string"),
     *     @SWG\Response(
     *         response=204,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @param $id
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        try {
            $this->service->remove($id);

            $cart = $this->service->getCart();
            $cost = $cart->getCost();
            return ['data'=>
                            ['checkoutLineItemsRemove'=>DataHelper::serializeCart($cart,$cost)]
                ];

        } catch (\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage(), null, $e);
        }
    }

    /**
     * @SWG\Delete(
     *     path="/shop/cart",
     *     tags={"Cart"},
     *     @SWG\Response(
     *         response=204,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @throws BadRequestHttpException
     */
    public function actionClear()
    {
        try {
            $this->service->clear();
            Yii::$app->getResponse()->setStatusCode(201);
            return ['Success' => true];
        } catch (\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage(), null, $e);
        }
    }
}

/**
 * @SWG\Definition(
 *     definition="Cart",
 *     type="object",
 *     @SWG\Property(property="weight", type="integer"),
 *     @SWG\Property(property="amount", type="integer"),
 *     @SWG\Property(property="items", type="array", @SWG\Items(
 *         type="object",
 *         @SWG\Property(property="id", type="string"),
 *         @SWG\Property(property="quantity", type="integer"),
 *         @SWG\Property(property="price", type="integer"),
 *         @SWG\Property(property="cost", type="integer"),
 *         @SWG\Property(property="product", type="object",
 *             @SWG\Property(property="id", type="integer"),
 *             @SWG\Property(property="code", type="string"),
 *             @SWG\Property(property="name", type="string"),
 *             @SWG\Property(property="thumbnail", type="string"),
 *             @SWG\Property(property="_links", type="object",
 *                 @SWG\Property(property="self", type="object", @SWG\Property(property="href", type="string")),
 *             )
 *         ),
 *         @SWG\Property(property="modification", type="object",
 *             @SWG\Property(property="id", type="integer"),
 *             @SWG\Property(property="code", type="string"),
 *             @SWG\Property(property="name", type="string"),
 *             @SWG\Property(property="_links", type="object",
 *                 @SWG\Property(property="quantity", type="object", @SWG\Property(property="href", type="string")),
 *             )
 *         )
 *     )),
 *     @SWG\Property(property="cost", type="object",
 *         @SWG\Property(property="origin", type="integer"),
 *         @SWG\Property(property="discounts", type="array", @SWG\Items(
 *             type="object",
 *             @SWG\Property(property="name", type="string"),
 *             @SWG\Property(property="value", type="integer")
 *         )),
 *         @SWG\Property(property="total", type="integer"),
 *     ),
 *     @SWG\Property(property="_links", type="object",
 *         @SWG\Property(property="self", type="object", @SWG\Property(property="href", type="string")),
 *     )
 * )
 */