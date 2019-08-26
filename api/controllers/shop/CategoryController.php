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
use Yii;

class CategoryController extends Controller
{
    private $products;
    private $categories;
    private $brands;
    private $tags;

    public function __construct(
        $id,
        $module,
        ProductReadRepository $products,
        CategoryReadRepository $categories,
        BrandReadRepository $brands,
        TagReadRepository $tags,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->products = $products;
        $this->categories = $categories;
        $this->brands = $brands;
        $this->tags = $tags;
    }

    protected function verbs(): array
    {
        return [
            'index' => ['GET'],
        ];
    }

    /**
     * @SWG\Get(
     *     path="/shop/collections",
     *     @SWG\Parameter(name="keyword", in="path", required=false, type="string"),
     *     tags={"Catalog"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/CategoryItem")
     *         ),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */

    public function actionIndex()
    {
        $keyword = Yii::$app->request->getQueryParam('keyword');

        $categories = $this->categories->getAll();
        $category_array = [];
        foreach($categories as $category){
            array_push($category_array,DataHelper::serializeCategory($category));
        }
        return $this->dataHeader($category_array);
        
    }


    public function actionGetBrands()
    {
        $keyword = Yii::$app->request->getQueryParam('keyword');

        $brands = $this->brands->getAll();
        $brand_array = [];
        foreach($brands as $brand){
            array_push($brand_array,DataHelper::serializeBrand($brand));
        }
        return $this->dataHeader($brand_array);
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


    public function dataHeader($categories)
    {
        return  
        ["data" => 
                ["shop" =>
                    [
                        "name" => "all you media",
                        "description" => "",
                        "collections" => 
                        [
                            "pageInfo" => 
                            [
                                "hasNextPage" => false,
                                "hasPreviousPage" => false,
                            ],
                            "edges" => $categories,
                    ]
                ]
            ]
        ];
    }

  
}

