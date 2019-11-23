<?php

namespace api\helpers;

use common\models\membership\Membership;
use shop\entities\Shop\Product\Modification;
use shop\entities\Shop\Product\Photo;
use shop\entities\Shop\Product\Product;
use shop\cart\CartItem;
use shop\cart\cost\Discount;
use common\models\currency\Currency;
use common\models\industry\Industry;
use yii\data\DataProviderInterface;
use yii\helpers\Url;
use common\services\UserAccessService;
use common\services\UserAddressService;
use common\models\userprofileimage\UserProfileImage;
use shop\helpers\UserHelper;
use api\helpers\DateHelper;
use shop\entities\User\User;
use common\services\NotificationService;


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
                'status' => $product->status,
                'weight' => $product->weight,
                'isLocked' => $product->is_locked,
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
                            'id' => $photo->id,
                            'src' => $photo->getThumbFileUrl('file', 'catalog_list'),
                            'sort' => $photo->sort
                           
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

    public static function serializeProductShort($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'talent_id' => $product->talent_id
        ];
    }
    
    /*** Category Serialization Methods */
    public static function serializeCategory($category) {
        return [

                'id' => $category->id,
                'title' => $category->name,
                "description" => $category->description,
                "onlineStoreUrl" => "",
                "image" => []
               
            ];
    }

    /*** Brand Serialization Methods */

    public static function serializeBrand($brand) {
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

    public static function cartLineItems($items) {
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
                        'plan'=>self::serializeMemberShipItem($subscription->ref_id),
            ];
        }

        return $memberships;

    }

    public static function serializeMemberShipItem($id){
        $model = Membership::find()->where("id = :id",['id'=>$id])->one();
        
        if($model!=null && $model instanceof Membership) {
            return self::serializeMemberShipPlan($model,true);
        }
        return [];

    }


    public static function serializeMemberShipItemSignUp($id){
        $model = Membership::find()->where("id = :id",['id'=>$id])->one();
        
        if($model!=null && $model instanceof Membership) {
            return self::serializeMemberShipPlan($model,true);
        }
        return [];

    }


    public static function serializeMemberShipPlan($plan,$includeGroup=false){
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
            "group" => ($includeGroup)?self::getGroupPlans($plan):[]
        ];
    }

    public static function getGroupPlans($plan){
            $groupItems = Membership::find()
                                        ->where("group_id = :gid AND id <> :id " ,
                                                 [':gid'=>$plan->group_id,
                                                 ":id"=>$plan->id])
                                        ->all();
            
            $response = [];
            foreach($groupItems as $item){
                $response[] = self::serializeMemberShipPlan($item);
            }
            return $response;
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

    public static function serializeTalents($talent){
        return [
            'id' => $talent->id,
            'profile' => static::serializeUserShort($talent->user),
            'industry' => static::serializeIndustry($talent->industry),
            'talent' => static::serializeTalent($talent->talent),
            'gender' => $talent->gender,
            'djGenre' => static::serializeDjGenre($talent->djgenre),
            'instrument' => static::serializeInstrument($talent->instrument),
            'instrumentSpec' => static::serializeInstrumentSpec($talent->instrumentspecification),
            'musicGenre' => static::serializeMusicGenre($talent->musicgenre),
            'created_at' => $talent->created_at,
            'created_by' => $talent->created_by,
            'modified_at' => $talent->modified_at,
            'modified_by' => $talent->modified_by,
        ];
    }

    public static function serializeIndustry($industry){
        return [
            'id' => $industry->id,
            'name' => $industry->name,
        ];
    }

    public static function serializeTalent($talent){
        return [
            'id' => $talent->id,
            'name' => $talent->name,
        ];
    }

    public static function serializeDjGenre($dj){
        return [
            'id' => $dj->id,
            'name' => $dj->name,
        ];
    }

    public static function serializeInstrument($instrument){
        return [
            'id' => $instrument->id,
            'name' => $instrument->name,
        ];
    }

    public static function serializeInstrumentSpec($instrumentSpec){
        return [
            'id' => $instrumentSpec->id,
            'name' => $instrumentSpec->name,
        ];
    }

    public static function serializeMusicGenre($music){
        return [
            'id' => $music->id,
            'name' => $music->name,
        ];
    }

    public static function serializeFunds($fund){
        return [
            "id" => $fund['id'],
            "name" => $fund['name'],
            "type" => $fund['type'],
            "amount" => $fund['amount'],
            "referral_code" => $fund['referral_code'],
            "referral_user" => $fund['referral_user'],
            "transaction_id" => $fund['transaction_id'],
            "created_at" => $fund['created_at'],
        ];
    }

    public static function serializeAddress($address){
        
        return [
            'address' => $address->address,
            'area'=> $address->area,
            'city'=> $address->city,
            'country'=> ($address->country!=null)?self::serializeCountry($address->country):'',
            'created_at' => $address->created_at,
            'created_by'=> $address->created_by,
            'default'=> $address->default,
            'first_name'=> $address->first_name,
            'id'=> $address->id,
            'is_deleted'=> $address->is_deleted,
            'last_name'=> $address->last_name,
            'modified_at'=> $address->modified_at,
            'modified_by'=> $address->modified_by,
            'phone_number'=> $address->phone_number,
            'postal_code'=> $address->postal_code,
            'state' => $address->state,
            'user_id' => $address->user_id,
        ];
    }

    public static function serializeCountry($country){
        return [
            'id'=>$country->id,
            'country_name'=>$country->title,
            'iso'=>$country->iso_code_2
        ];
    }

    public static function serializeUserShort($user){

        // d($user);
        if(is_numeric($user->country)){
            $cc = UserAddressService::getCountryFrmId((int)$user->country);
            $country = DataHelper::serializeCountry($cc);
        }else{
           
            $country = $user->country;
        }
        
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'country' => $country,
            'city' => $user->city,
            'state' => $user->state,
            'profileImage' => UserProfileImage::getProfileImage($user->id),
            'bannerImage' => UserProfileImage::getBannerImage($user->id),
            'referralCode' => $user->referral_code,
        ];
    }

    public static function serliazeMessageThread($thread){
        return [
            'id' => $thread['id'],
            "title" => $thread['title'],
            "description" => $thread['description'],
            "creator" => $thread['creator'],
            "unread_count" => $thread['unread_count'],
            "created_by" => self::serializeUserShort($thread['created_by']),
            "created_at" => DateHelper::formatDate($thread['created_at']),
            "participants"=> self::serliazeParticipants($thread["participants"])
         
        ];
    }

    public static function serliazeParticipants($participants){
        $response = [];
        foreach($participants as $participant){
              $response[] = self::serializeUserShort($participant->user);  
        }
        return $response;
    }

    public static function serliazeMessage($message){
       
        return [
            'id' => $message->id,
            "body" => $message->body,
            "created_at" => DateHelper::formatDate($message->created_at),
            "modified_at" => $message->modified_at,
            "thread_id" => $message->thread_id,
            "created_by" => self::serializeUserShort($message->created)
        ];
    }

    public static function serializeUser(User $user): array
    {
        
        $addresses = self::getSerializeUserAddresses($user->id);
        $defaultAddress = self::getSerializeDefaultAddress($addresses);
        $subscriptions = $user->getSubscription('membership');
        $country = [];

        if($user->country){
            $cc = UserAddressService::getCountryFrmId((int)$user->country);
            $country = self::serializeCountry($cc);
        }

        $talents = [];
        foreach($user->userTalent as $talent)
            $talents[] = self::serializeTalents($talent);
 
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'country' => $country,
            'city' => $user->city,
            'state' => $user->state,
            'profileImage' => UserProfileImage::getProfileImage($user->id),
            'bannerImage' => UserProfileImage::getBannerImage($user->id),
            'signup_plan' => self::serializeMemberShipItemSignUp($user->membership_id),
            'referralCode' => $user->referral_code,
            'date' => [
                'created' => DateHelper::formatApi($user->created_at),
                'updated' => DateHelper::formatApi($user->updated_at),
            ],
            'status' => [
                'code' => $user->status,
                'name' => UserHelper::statusName($user->status),
            ],
            'membership' =>  self::serializeMemberShips($subscriptions),
            'addresses'=> $addresses,
            'defaultAddress' => $defaultAddress,
            'talent' => $talents,
            'update_talent_profile' => $user->canUpdateProfile(),
        ];
    }

    
    public static function getSerializeUserAddresses($uid=''){
        $userAddressArr = [];
        $uid = ($uid!='')?$uid:\Yii::$app->user->id;
        $userAddress = UserAddressService::userAddress('get', $uid);
        foreach($userAddress as $address){
            $addressArr = self::serializeAddress($address);    
            array_push($userAddressArr,$addressArr);
        }
        return $userAddressArr;
    }

    public function getSerializeDefaultAddress($addresses){
        foreach($addresses as $address){
            if($address['default']===1){
                return $address;
            }
        }
        return null;
    }
    
    public static function serliazeNotification($notification) {
        $originator = $notification->originator;
        $user = $notification->user;
        $model = $notification->source_class;
        $source = $model::findOne($notification->source_pk);
        $class = \Yii::$container->get($notification->class);

        return [
            'title' => $class->title,
            'message' => self::generateNotificationMessage($user, $originator, $source, $class),
            'event_key' => $class->notificationEventKey,
            'created_at' => $notification->created_at,
            'seen' => $notification->seen,
            'source' => $source,
            'originator' => self::serializeUserShort($originator),
        ];
    }
    
    public static function serializeNotificationSetting($notificationSetting) {
        return [
            'id' => $notificationSetting['id'],
            'event' => $notificationSetting['event'],
            'email' => $notificationSetting['email'] ?? 0,
            'web' => $notificationSetting['web'] ?? 0,
            'mobile' => $notificationSetting['mobile'] ?? 0,
        ];        
    }
    
    private static function generateNotificationMessage($user, $originator, $source, $class) {
        return \Yii::$app->controller->renderPartial('@common/modules/' . $class->moduleId . '/views/' . $class->viewNameText, ['originator' => $originator, 'user' => $user, 'source' => $source]);
    }
}