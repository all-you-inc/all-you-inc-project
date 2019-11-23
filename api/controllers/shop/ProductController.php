<?php

namespace api\controllers\shop;

use api\providers\MapDataProvider;
use shop\entities\Shop\Category;
use shop\entities\Shop\Product\Modification;
use shop\entities\Shop\Product\Photo;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Tag;
use shop\readModels\Shop\CategoryReadRepository;
use shop\readModels\Shop\TagReadRepository;
use shop\readModels\Shop\BrandReadRepository;
use shop\readModels\Shop\ProductReadRepository;
use yii\data\DataProviderInterface;
use yii\helpers\Url;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use api\helpers\DataHelper;
use backend\forms\Shop\ProductSearch;
use shop\forms\manage\Shop\Product\ProductCreateForm;
use shop\useCases\manage\Shop\ProductManageService;
use shop\forms\manage\Shop\Product\QuantityForm;
use shop\forms\manage\Shop\Product\PhotosForm;
use shop\forms\manage\Shop\Product\PriceForm;
use shop\forms\manage\Shop\Product\ProductEditForm;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use shop\entities\Shop\Product\Value;
use Yii;
use common\models\promo\Promo;

class ProductController extends Controller
{
    private $products;
    private $categories;
    private $brands;
    private $tags;
    private $service;

    public function __construct(
        $id,
        $module,
        ProductReadRepository $products,
        CategoryReadRepository $categories,
        BrandReadRepository $brands,
        TagReadRepository $tags,
        ProductManageService $service,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->products = $products;
        $this->categories = $categories;
        $this->brands = $brands;
        $this->tags = $tags;
        $this->service = $service;
    }

    protected function verbs(): array
    {
        return [
            'index' => ['GET'],
            'related' => ['GET'],
            'category' => ['GET'],
            'brand' => ['GET'],
            'tag' => ['GET'],
            'view' => ['GET'],

            'create' => ['POST'],
            'price' => ['PUT'],
            'quantity' => ['PUT'],
            'update' => ['POST'],
            'delete' => ['DELETE'],
            'activate' => ['POST'],
            'draft' => ['POST'],
            'delete-photo' => ['POST'],
            'move-photo-up' => ['POST'],
            'move-photo-down' => ['POST'],
        ];
    }

    /**
     * @SWG\Get(
     *     path="/shop/products",
     *     @SWG\Parameter(name="keyword", in="path", required=false, type="string"),
     *     tags={"Catalog"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/ProductItem")
     *         ),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */

    public function actionIndex()
    {
        $keyword = Yii::$app->request->getQueryParam('keyword');
        $talent = Yii::$app->request->getQueryParam('talent');

        $dataProvider = $this->products->getAllProducts($keyword,$talent);
        
        $product_array = [];
        foreach($dataProvider->getModels() as $product){
            array_push($product_array,DataHelper::serializeProduct($product));
        }
        return $this->dataHeader($product_array);
    }

    // $params = [];
    // $params['id'] = $id;
    // $params['code'] = $ref_code;
    // $params['type'] =  'product';

    public function actionStoreProductPromoSession()
    {
        if(\Yii::$app->request->post())
        {
            $i=0;
            $dataErrors = [];
            $dataObject = isset(\Yii::$app->request->post()['data']) ? 
                                    \Yii::$app->request->post()['data'] : '' ;

            if($dataObject == null){
                return [ 
                    'status' => 400,
                    'message' => 'Data not found',
                ];
            }
            if(!is_array($dataObject)){
                return [ 
                    'status' => 400,
                    'message' => 'Data need in array',
                ];
            }
            
            foreach($dataObject as $params)
            {
                $user_id = Yii::$app->user->id;

                if(!is_array($params)){
                    $error = [ 
                        'arrayKey' => $i,
                        'state' => 'error',
                        'message' => 'Data need in array',
                    ];
                    array_push($dataErrors,$error);
                    $i++;
                    continue;
                }
                if(!isset($params['id']) || $params['id'] == null){
                    $error = [ 
                        'arrayKey' => $i,
                        'state' => 'error',
                        'message' => 'id not found',
                    ];
                    array_push($dataErrors,$error);
                    $i++;
                    continue;
                }
                if (!$product = $this->products->find($params['id'])) {
                    $error = [ 
                        'arrayKey' => $i,
                        'state' => 'error',
                        'message' => 'product not found',
                    ];
                    array_push($dataErrors,$error);
                    $i++;
                    continue;
                }
                if(!isset($params['type']) || $params['type'] == null){
                    $error = [ 
                        'arrayKey' => $i,
                        'state' => 'error',
                        'message' => 'type not found',
                    ];
                    array_push($dataErrors,$error);
                    $i++;
                    continue;
                }
                if(!isset($params['code']) || $params['code'] == null){
                    $error = [ 
                        'arrayKey' => $i,
                        'state' => 'error',
                        'message' => 'code not found',
                    ];
                    array_push($dataErrors,$error);
                    $i++;
                    continue;
                }

                $isPromoExist = Promo::find()
                            ->where([
                                'user_id' => $user_id,
                                'ref_id' => $params['id'],
                                'type' => $params['type']])
                            ->one();

                if (!$isPromoExist) {
                    $time = time();
                    $promo = new Promo();
                    $promo->user_id = $user_id;
                    $promo->ref_id = $params['id'];
                    $promo->type = $params['type'];
                    $promo->referral_code = $params['code'];
                    $promo->created_at = $time;
                    $promo->modified_at = $time;
                    $promo->created_by = $user_id;
                    $promo->modified_by = $user_id;
                    if (!$promo->save()) 
                    {
                        $error = [ 
                            'arrayKey' => $i,
                            'error'=> [
                                    $promo->errors,
                                ],
                            'state' => 'error',
                            'message' => 'Invalid data',
                        ];
                        array_push($dataErrors,$error);
                        $i++;
                        continue;
                    }
                    $success = [
                        'arrayKey' => $i,
                        'state' => 'success',
                        'message' => 'Promo add successfully',
                    ];
                    array_push($dataErrors,$success);
                    $i++;
                    continue;
                }
                $error = [ 
                    'arrayKey' => $i,
                    'state' => 'error',
                    'message' => 'Promo entry already exist',
                ];
                array_push($dataErrors,$error);
                $i++;
                continue;
            }
            return [
                'status' => 200,
                'data' => $dataErrors,
                'message' => 'Process Complete',
            ];
        }
        return [ 
            'status' => 400,
            'message' => 'Request must be (POST)',
        ];
    }

    public function actionUserCollection()
    {
        $keyword = Yii::$app->request->getQueryParam('keyword');
        
        $dataProvider = $this->products->getUserProducts(\Yii::$app->user->id,$keyword);
        
        $product_array = [];
        foreach($dataProvider->getModels() as $product){
            array_push($product_array,DataHelper::serializeProduct($product));
        }
        return $this->dataHeader($product_array);
    }

    

    /**
     * @SWG\Get(
     *     path="/shop/products/category/{categoryId}",
     *     tags={"Catalog"},
     *     @SWG\Parameter(name="categoryId", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/ProductItem")
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @param $id
     * @return DataProviderInterface
     * @throws NotFoundHttpException
     */
    public function actionCategory($id)
    {
        if (!$category = $this->categories->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $dataProvider = $this->products->getAllByCategory($category);

        $product_array = [];
        foreach($dataProvider->getModels() as $product){
            array_push($product_array,DataHelper::serializeProduct($product));
        }
        return $this->dataHeader($product_array);
    }

    /**
     * @SWG\Get(
     *     path="/shop/products/related/{type}",
     *     tags={"Catalog"},
     *     @SWG\Parameter(name="type", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/ProductItem")
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @param $id
     * @return DataProviderInterface
     * @throws NotFoundHttpException
     */
    public function actionRelated($id)
    {
        $type = Yii::$app->request->getQueryParam("type");
       
        $dataProvider = $this->products->getAllByCategoryName($type,$id);

        $product_array = [];
        foreach($dataProvider->getModels() as $product){
            array_push($product_array,DataHelper::serializeProduct($product));
        }
        return $this->dataHeader($product_array);

    }




    /**
     * @SWG\Get(
     *     path="/shop/products/brand/{brandId}",
     *     tags={"Catalog"},
     *     @SWG\Parameter(name="brandId", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/ProductItem")
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @param $id
     * @return DataProviderInterface
     * @throws NotFoundHttpException
     */
    public function actionBrand($id): DataProviderInterface
    {
        if (!$brand = $this->brands->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $dataProvider = $this->products->getAllByBrand($brand);
        return new MapDataProvider($dataProvider, [$this, 'serializeListItem']);
    }

    /**
     * @SWG\Get(
     *     path="/shop/products/tag/{tagId}",
     *     tags={"Catalog"},
     *     @SWG\Parameter(name="tagId", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/ProductItem")
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @param $id
     * @return DataProviderInterface
     * @throws NotFoundHttpException
     */
    public function actionTag($id): DataProviderInterface
    {
        if (!$tag = $this->tags->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $dataProvider = $this->products->getAllByTag($tag);
        return new MapDataProvider($dataProvider, [$this, 'serializeListItem']);
    }

    public function actionCreate() {
        $form = new ProductCreateForm('api');

        if(isset(Yii::$app->request->post()['ProductEditForm']['talent_id']) && Yii::$app->request->post()['ProductEditForm']['talent_id'] != null){
            $talent_id =  Yii::$app->request->post()['ProductEditForm']['talent_id'];
            $talentChecker = true;
            $userTalents = $form->talentList();
            if($userTalents != null){
                foreach($userTalents as $key => $talent){
                    if($key == $talent_id){
                        $talentChecker = false;
                    }
                }
                if($talentChecker){
                    return [ 
                        'status' => '400',
                            'data'=>[
                                'quantityUpdate'=>[
                                        'Product'=> null,
                                        'TalentErrors'=> 'User Talent Id Is Wrong',
                                ],
                            ],
                        'message' => 'Invalid Data',
                    ];
                }
            }else
            {
                return [ 
                    'status' => '400',
                        'data'=>[
                            'quantityUpdate'=>[
                                    'Product'=> null,
                                    'TalentErrors'=> 'User Talent Not Found',
                            ],
                        ],
                    'message' => 'Invalid Data',
                ];
            }
        }

        if($form->load(Yii::$app->request->post()))
        {
            if ($form->validate()) 
            {
                try {
                    $product = $this->service->create($form);
                    return [ 
                        'status' => 200,
                        'data'=>[
                            'productCreate'=>$this->serializeView($product),
                            
                            ],
                        'message' => '',
                    ];
                    return ;
                } catch (\DomainException $e) {
                    return [ 
                        'status' => 400,
                        'data'=>[
                            'productCreate'=>[
                                    'ProductErrors'=> $form->errors,
                                    'QuantityErrors'=> $form->quantity->errors,
                                    'CategoryErrors'=> $form->categories->errors,
                                    'PriceErrors'=> $form->price->errors,
                                    'PhotoErrors'=> $form->photos->errors,
                                    'DomainException' => $e->getMessage(),
                            ],
                            
                            ],
                        'message' => 'Invalid Data',
                    ];
                }
            }
            return [ 
                'status' => 400,
                'errors'=> array_merge(
                                    $form->errors,$form->quantity->errors,
                                    $form->categories->errors,$form->price->errors,
                                    $form->photos->errors
                                    ),
                    
                    
                'message' => 'Invalid Data',
            ];
        }
        return [ 
            'status' => 400,
            'error'=>
                [
                    array_merge($form->errors,$form->quantity->errors,$form->categories->errors,$form->price->errors,$form->photos->errors)
                ],
                
                
            'message' => 'Post Data not loaded.',
        ];
    }

    public function actionPrice($id)
    {
        $product = $this->findModel($id);

        $form = new PriceForm($product);
        if($form->load(Yii::$app->request->post())){
            if ($form->validate()) {
                try {
                    $this->service->changePrice($product->id, $form);
                    return [
                        'status' => '200',
                        'message' => 'Price Update Successfully',
                    ];
                } catch (\DomainException $e) {
                    return [ 
                        'status' => '400',
                        'data'=>[
                                'priceUpdate'=>[
                                        'ProductPrice'=> null,
                                        'PriceErrors'=> $e->getMessage(),
                                ],
                            ],
                        'message' => 'Invalid Data',
                    ];
                }
            }
            return [ 
                'status' => '400',
                    'data'=>[
                        'priceUpdate'=>[
                                'ProductPrice'=> null,
                                'PriceErrors'=> $form->errors
                        ],
                    ],
                'message' => 'Invalid Data',
            ];
        }
        return [ 
            'status' => '400',
                'data'=>[
                    'priceUpdate'=>[
                            'ProductPrice'=> null,
                            'PriceErrors'=> 'Posted data not loaded in model'
                    ],
                ],
            'message' => 'Invalid Data',
        ];
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionQuantity($id)
    {
        $product = $this->findModel($id);

        $form = new QuantityForm($product);
        if($form->load(Yii::$app->request->post())){
            if ($form->validate()) {
                try {
                    $this->service->changeQuantity($product->id, $form);
                    return [
                        'status' => '200',
                        'message' => 'Quantity Update Successfully',
                    ];
                } catch (\DomainException $e) {
                    return [ 
                        'status' => '400',
                            'data'=>[
                                'quantityUpdate'=>[
                                        'ProductQuantity'=> null,
                                        'QuantityErrors'=> $e->getMessage(),
                                ],
                            ],
                        'message' => 'Invalid Data',
                    ];
                }
            }
            return [ 
                'status' => '400',
                    'data'=>[
                        'quantityUpdate'=>[
                                'ProductQuantity'=> null,
                                'QuantityErrors'=> $form->errors
                        ],
                    ],
                'message' => 'Invalid Data',
            ];
        }
        return [ 
            'status' => '400',
                'data'=>[
                    'quantityUpdate'=>[
                            'ProductQuantity'=> null,
                            'QuantityErrors'=> 'Posted data not loaded in model'
                    ],
                ],
            'message' => 'Posted data not loaded in model',
        ];
    }

    public function actionDelete($id)
    {
        try {
            $this->service->remove($id);
            return [
                'status' => '200',
                'message' => 'Product Delete Successfully',
            ];
        } catch (\DomainException $e) {
            return [ 
                'status' => '400',
                    'data'=>[
                        'quantityUpdate'=>[
                                'Product'=> null,
                                'ProductErrors'=> $e->getMessage(),
                        ],
                    ],
                'message' => 'Invalid Data',
            ];
        }
    }

    public function actionUpdate($id)
    {
        $product = $this->findModel($id);

        $form = new ProductEditForm($product,'api');
        $photosForm = new PhotosForm();

        $photosDeleteArr = [];

        if(isset(Yii::$app->request->post()['DeletePhoto']) && Yii::$app->request->post()['DeletePhoto'] != null){
            if(is_array(Yii::$app->request->post()['DeletePhoto'])){
                $photosDeleteArr = Yii::$app->request->post()['DeletePhoto'];
            }
            else
            {
                return [ 
                    'status' => '400',
                        'data'=>[
                            'ProductUpdate'=>[
                                    'Product'=> null,
                                    'DeletePhotoErrors'=> 'Delete Photo Array Required',
                            ],
                        ],
                    'message' => 'Invalid Data',
                ];
            }
        }
      
        if(isset(Yii::$app->request->post()['ProductEditForm']['talent_id']) 
        && Yii::$app->request->post()['ProductEditForm']['talent_id'] != null){

            
            $talent_id =  Yii::$app->request->post()['ProductEditForm']['talent_id'];
            $talentChecker = true;
            $userTalents = $form->talentList();

            if($userTalents != null){
                foreach($userTalents as $key => $talent){
                    if($key == $talent_id){
                        $talentChecker = false;
                    }
                }
                if($talentChecker){
                    return [ 
                        'status' => '400',
                            'data'=>[
                                'productUpdate'=>[
                                        'Product'=> null,
                                        'TalentErrors'=> 'User Talent Id Is Wrong',
                                ],
                            ],
                        'message' => 'Invalid Data',
                    ];
                }
            }else
            {
                return [ 
                    'status' => '400',
                        'data'=>[
                            'productUpdate'=>[
                                    'Product'=> null,
                                    'TalentErrors'=> 'User Talent Not Found',
                            ],
                        ],
                    'message' => 'Invalid Data',
                ];
            }
        }
        
        if($form->load(Yii::$app->request->post()))
        {   
           
            if ($photosForm->load(Yii::$app->request->post()))
            {
                if ($form->validate()) 
                {
                    if ($photosForm->validate()) 
                    {
                        try 
                        {
                            if($photosDeleteArr != null){
                                foreach($photosDeleteArr as $deletePhotoId)
                                {
                                    $this->service->removePhoto($product->id, $deletePhotoId);
                                }
                            }
                            $this->service->addPhotos($product->id, $photosForm);

                            $this->service->edit($product->id, $form);
                            return [
                                'status' => '200',
                                'message' => 'Product Update Successfully',
                                'data' => [
                                    'productUpdate'=>$this->serializeView($product),
                                ]
                            ];
                        } catch (\DomainException $e)
                        {
                            return [ 
                                'status' => '400',
                                    'data'=>[
                                        'productUpdate'=>[
                                                'Product'=> null,
                                                'ProductErrors'=> $e->getMessage(),
                                        ],
                                    ],
                                'message' => 'Invalid Data',
                            ];
                        }
                    }
                    return [ 
                        'status' => '400',
                            'data'=>[
                                'productUpdate'=>[
                                        'Product'=> null,
                                        'photoErrors'=> $photosForm->errors,
                                ],
                            ],
                        'message' => 'Invalid Data',
                    ];
                }
                return [ 
                    'status' => '400',
                        'data'=>[
                            'productUpdate'=>[
                                    'Product'=> null,
                                    'ProductErrors'=> $form->errors,
                                    'MetaErrors' => $form->meta->errors,
                                    'CategoryErrors' => $form->categories->errors,
                            ],
                        ],
                    'message' => 'Invalid Data',
                ];
            }
            return [ 
                'status' => '400',
                    'data'=>[
                        'quantityUpdate'=>[
                                'productUpdate'=> null,
                                'ProductErrors'=> 'Photo Posted data not loaded in model',
                        ],
                    ],
                'message' => 'Invalid Data',
            ];
        }
        return [ 
            'status' => '400',
                'data'=>[
                    'quantityUpdate'=>[
                            'productUpdate'=> null,
                            'ProductErrors'=> 'Product Posted data not loaded in model',
                    ],
                ],
            'message' => 'Invalid Data',
        ];
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionActivate($id)
    {
        try {
            $this->service->activate($id);
            return [
                'status' => '200',
                'message' => 'Product Activate Successfully',
            ];
        } catch (\DomainException $e) {
            return [ 
                'status' => '400',
                    'data'=>[
                        'quantityUpdate'=>[
                                'Product'=> null,
                                'ProductErrors'=> $e->getMessage(),
                        ],
                    ],
                'message' => 'Invalid Data',
            ];
        }
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDraft($id)
    {
        try {
            $this->service->draft($id);
            return [
                'status' => '200',
                'message' => 'Product Draft Successfully',
            ];
        } catch (\DomainException $e) {
            return [ 
                'status' => '400',
                    'data'=>[
                        'quantityUpdate'=>[
                                'Product'=> null,
                                'ProductErrors'=> $e->getMessage(),
                        ],
                    ],
                'message' => 'Invalid Data',
            ];
        }
    }


    /**
     * @SWG\Get(
     *     path="/shop/products/{productId}",
     *     tags={"Catalog"},
     *     @SWG\Parameter(
     *         name="productId",
     *         description="ID of product",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/ProductView")
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * 
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionView($id): array
    {
        if (!$product = $this->products->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $this->serializeListItem($product);
    }



    public function serializeListItem(Product $product): array
    {
        return [
            "cursor" => strval($product->id),
            "node" => [

                'id' => $product->id,
                'title' => $product->name,
                "description" => $product->description,
                "availableForSale" => false,
                "productType" => $product->category->slug,
                'quantity' => $product->quantity,
                "onlineStoreUrl" => "",
                "options" => [],
                "variants" => [
                    "pageInfo" => [  
                        "hasNextPage"=>false,
                        "hasPreviousPage"=>false
                    ],
                    "edges" => [
                        $this->dataCharacteristic($product),
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

    private function serializeView(Product $product): array
    {
        return [
            'id' => $product->id,
            'code' => $product->code,
            'name' => $product->name,
            'description' => $product->description,
            'categories' => [
                'main' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    '_links' => [
                        'self' => ['href' => Url::to(['category', 'id' => $product->category->id], true)],
                    ],
                ],
                'other' => array_map(function (Category $category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        '_links' => [
                            'self' => ['href' => Url::to(['category', 'id' => $category->id], true)],
                        ],
                    ];
                }, $product->categories),
            ],
            'tags' => array_map(function (Tag $tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    '_links' => [
                        'self' => ['href' => Url::to(['tag', 'id' => $tag->id], true)],
                    ],
                ];
            }, $product->tags),
            'price' => [
                'new' => $product->price_new,
                'old' => $product->price_old,
            ],
            'photos' => array_map(function (Photo $photo) {
                return [
                    'thumbnail' => $photo->getThumbFileUrl('file', 'catalog_list'),
                    'origin' => $photo->getThumbFileUrl('file', 'catalog_origin'),
                ];
            }, $product->photos),
            'modifications' => array_map(function (Modification $modification) use ($product) {
                return [
                    'id' => $modification->id,
                    'code' => $modification->code,
                    'name' => $modification->name,
                    'price' => $product->getModificationPrice($modification->id),
                ];
            }, $product->modifications),
            'rating' => $product->rating,
            'weight' => $product->weight,
            'quantity' => $product->quantity,
            '_links' => [
                'self' => ['href' => Url::to(['view', 'id' => $product->id], true)],
                'wish' => ['href' => Url::to(['/shop/wishlist/add', 'id' => $product->id], true)],
                'cart' => ['href' => Url::to(['/shop/cart/add', 'id' => $product->id], true)],
            ],
        ];
    }

    public function dataHeader($products)
    {
        return  
        ["data" => 
                ["shop" =>
                    [
                        "name" => "all you media",
                        "description" => "",
                        "products" => 
                        [
                            "pageInfo" => 
                            [
                                "hasNextPage" => false,
                                "hasPreviousPage" => false,
                            ],
                            "edges" => $products,
                    ]
                ]
            ]
        ];
    }

    public function dataCharacteristic($product){
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

    protected function findModel($id): Product
    {
        if (($model = Product::find()->where(['id' => $id, 'created_by' => \Yii::$app->user->id])->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}



/**
 * @SWG\Definition(
 *     definition="ProductItem",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="code", type="string"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="category", ref="#/definitions/ProductCategory"),
 *     @SWG\Property(property="brand", ref="#/definitions/ProductBrand"),
 *     @SWG\Property(property="price", ref="#/definitions/ProductPrice"),
 *     @SWG\Property(property="thumbnail", type="string"),
 *     @SWG\Property(property="_links", type="object",
 *         @SWG\Property(property="self", type="object", @SWG\Property(property="href", type="string")),
 *     ),
 * )
 *
 * @SWG\Definition(
 *     definition="ProductView",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="code", type="string"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="description", type="string"),
 *     @SWG\Property(property="categories", type="object",
 *         @SWG\Property(property="main", ref="#/definitions/ProductCategory"),
 *         @SWG\Property(property="other", type="array", @SWG\Items(ref="#/definitions/ProductCategory")),
 *     ),
 *     @SWG\Property(property="brand", ref="#/definitions/ProductBrand"),
 *     @SWG\Property(property="tags", type="array", @SWG\Items(ref="#/definitions/ProductTag")),
 *     @SWG\Property(property="photos", type="array", @SWG\Items(ref="#/definitions/ProductPhoto")),
 *     @SWG\Property(property="_links", type="object",
 *         @SWG\Property(property="self", type="object", @SWG\Property(property="href", type="string")),
 *     ),
 * )
 *
 * @SWG\Definition(
 *     definition="ProductCategory",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="_links", type="object",
 *         @SWG\Property(property="self", type="object", @SWG\Property(property="href", type="string")),
 *     ),
 * )
 *
 * @SWG\Definition(
 *     definition="ProductBrand",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="_links", type="object",
 *         @SWG\Property(property="self", type="object", @SWG\Property(property="href", type="string")),
 *     ),
 * )
 *
 * @SWG\Definition(
 *     definition="ProductTag",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="_links", type="object",
 *         @SWG\Property(property="self", type="object", @SWG\Property(property="href", type="string")),
 *     ),
 * )
 *
 * @SWG\Definition(
 *     definition="ProductPrice",
 *     type="object",
 *     @SWG\Property(property="new", type="integer"),
 *     @SWG\Property(property="old", type="integer"),
 * )
 *
 * @SWG\Definition(
 *     definition="ProductPhoto",
 *     type="object",
 *     @SWG\Property(property="thumbnail", type="string"),
 *     @SWG\Property(property="origin", type="string"),
 * )
 */