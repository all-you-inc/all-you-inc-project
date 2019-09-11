<?php

namespace api\helpers;

use common\models\membership\Membership;
use shop\entities\Shop\Product\Modification;
use shop\entities\Shop\Product\Photo;
use shop\entities\Shop\Product\Product;
use shop\cart\CartItem;
use shop\cart\cost\Discount;
use common\models\currency\Currency;
use yii\data\DataProviderInterface;
use yii\helpers\Url;

class DataHelper
{
    /*** Product Serialization Methods */

    public static function serializeProduct($product)
    {
        return [
            "cursor" => strval($product->id),
            "node" => [

                'id' => $product->id,
                'title' => $product->name,
                'code' => $product->code,
                "description" => $product->description,
                "availableForSale" => false,
                "productType" => $product->category->name,
                'quantity' => $product->quantity,
                "onlineStoreUrl" => "",
                "options" => [],
                "variants" => [
                    "pageInfo" => [  
                        "hasNextPage"=>false,
                        "hasPreviousPage"=>false
                    ],
                    "edges" => [
                        self::productCharacteristic($product),
                    ]
                ],
                "images" => [
                    "pageInfo" => [  
                        "hasNextPage"=>false,
                        "hasPreviousPage"=>false
                    ],
                    "edges" => 
                      array_map(function (Photo $photo) {
                        return [
                            'src' => $photo->getThumbFileUrl('file', 'catalog_list')
                           
                        ];
                    }, $product->photos)
                ],
                'category' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    '_links' => [
                        'self' => ['href' => Url::to(['category', 'id' => $product->category->id], true)],
                    ],
                ],
                'brand' => [
                    'id' => $product->brand->id,
                    'name' => $product->brand->name,
                    '_links' => [
                        'self' => ['href' => Url::to(['brand', 'id' => $product->brand->id], true)],
                    ],
                ],
                'price' => [
                    'new' => $product->price_new,
                    'old' => $product->price_old,
                ],
                'thumbnail' => $product->mainPhoto ? $product->mainPhoto->getThumbFileUrl('file', 'catalog_list'): null,
                '_links' => [
                    'self' => ['href' => Url::to(['view', 'id' => $product->id], true)],
                    'wish' => ['href' => Url::to(['/shop/wishlist/add', 'id' => $product->id], true)],
                    'cart' => ['href' => Url::to(['/shop/cart/add', 'id' => $product->id], true)],
                ],
            ]
        ];
    }

    public static function productCharacteristic($product){
        $result = [];
        for($i = 0;$i<count($product->values);$i++){
            $result['id'] = $product->id;
            $result['title'] = $product->values[$i]->characteristic->name;
            $result["selectedOptions"][$i] = ['name' => $product->values[$i]->characteristic->name, 'value'=> $product->values[$i]->value];
            $result["image"] = $product->mainPhoto ? $product->mainPhoto->getThumbFileUrl('file', 'catalog_list'): null;
            $result["price"] = $product->price_new;
            $result["compareAtPrice"] = "";
        }
        return $result;
    }
    /** */

    /*** Category Serialization Methods */
    public static function serializeCategory($category)
    {
        return [

                'id' => $category->id,
                'title' => $category->name,
                "description" => $category->description,
                "onlineStoreUrl" => "",
                "image" => []
               
            ];
    }

    /*** Brand Serialization Methods */

    public static function serializeBrand($brand)
    {
        return [

                'id' => $brand->id,
                'title' => $brand->name,
                "slug" => $brand->slug,
                "onlineStoreUrl" => "",
                "image" => []
               
            ];
    }


    /*** Cart Serialization Methods */

    public static function serializeCart($cart,$cost)
    {
        return [
        'checkout'=>
         [
            'weight' => $cart->getWeight(),
            'amount' => $cart->getAmount(),
            'webUrl' => "",
            'lineItems' => self::cartLineItems($cart->getItems()),
            'paymentDue'=>$cart->getAmount(),
            'subtotalPrice'=>$cart->getAmount(),
            'totalPrice'=>$cost->getTotal(),
            'discounts' => array_map(function (Discount $discount) {
                return [
                    'name' => $discount->getName(),
                    'value' => $discount->getValue(),
                ];
            }, $cost->getDiscounts()),
            
            '_links' => [
                'checkout' => ['href' => Url::to(['/shop/checkout/index'], true)],
            ],
         ]
        ];


    }

    public static function cartLineItems($items){
        $lineItems = [];

        foreach($items as $item){
            $product = $item->getProduct();
             $modification = $item->getModification();
               
            $lineItems[] = [
                'id' => $item->getId(),
                'title' => $product->name,
                'quantity' => $item->getQuantity(),
                'price' => $item->getPrice(),
                'cost' => $item->getCost(),
                'product' => self::serializeProduct($product),
                'modification' => $modification ? [
                    'id' => $product->id,
                    'code' => $modification->code,
                    'name' => $modification->name,
                ] : [],
                '_links' => [
                    'quantity' => ['href' => Url::to(['quantity', 'id' => $item->getId()], true)],
                ],
            ];
        }
        return $lineItems;



    }
    /*** */

    public static function serializeMemberShips($subscriptions){
        $memberships = [];

        foreach($subscriptions as $subscription){
            $memberships[] = [
                        'subscription_id'=>$subscription->id,
                        'membership'=>self::serializeMemberShipItem($subscription->ref_id),
            ];
        }

        return $memberships;

    }

    public static function serializeMemberShipItem($id){
        $model = Membership::find()->where("id = :id",['id'=>$id])->one();
        
        if($model!=null && $model instanceof Membership) {
            return ['id'=>$model->id,'title'=>$model->title,'price'=>$model->price];
        }
        return [];

    }

    public static function serializeMemberShipPlan($plan){
        return [
            "id" => $plan->id,
            "title" => $plan->title,
            "sort" => $plan->sort,
            "price" => $plan->price,
            "currency" => static::serializeCurrency($plan->currency),
            "status" => $plan->status,
            "description" => $plan->description,
            "category" => $plan->category,
            "items" => static::serializeMSItems($plan->msItems),
        ];
    }

    public static function serializeMSItems($msItems){
        $msItemsArr = [];
        $i = 0;
        foreach($msItems as $msItem){
            $msItemsArr[$i] = [
                'id' => $msItem->id,
                'unit' => $msItem->unit,
                'type' => $msItem->type,
                'itemType' => static::serializeMSItemType($msItem->itemType),
                'price' => $msItem->price,
                'currency' => static::serializeCurrency($msItem->currency),
                'groupId' => $msItem->group_id,
            ];
            $i++;
        }
        return $msItemsArr;
    }

    
    public static function serializeCurrency($currency){
        return [
            'id' => $currency->id,
            'title' => $currency->title,
        ];
    }

    public static function serializeMSItemType($type){
        return [
            'id' => $type->id,
            'title' => $type->title,
            'key' => $type->key
        ];
    }
}