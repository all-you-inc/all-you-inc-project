<?php

namespace frontend\controllers\shop;

use shop\forms\Shop\AddToCartForm;
use shop\forms\Shop\ReviewForm;
use shop\forms\Shop\Search\SearchForm;
use shop\readModels\Shop\BrandReadRepository;
use shop\readModels\Shop\CategoryReadRepository;
use shop\readModels\Shop\ProductReadRepository;
use shop\readModels\Shop\TagReadRepository;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Category;
use shop\entities\Shop\Brand;
use yii\helpers\ArrayHelper;

class CatalogController extends Controller {

    public $layout = 'catalog';
    private $products;
    private $categories;
    private $brands;
    private $tags;

    public function __construct(
    $id, $module, ProductReadRepository $products, CategoryReadRepository $categories, BrandReadRepository $brands, TagReadRepository $tags, $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->products = $products;
        $this->categories = $categories;
        $this->brands = $brands;
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function actionIndex() {
        $this->layout = 'main';
        $dataProvider = Product::find()->all();
        $categories = Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft')->all();
        $brands = Brand::find()->orderBy('name')->all();
        return $this->render('index', [
                    'brands' => $brands,
                    'categories' => $categories,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCategory($id) {
        $this->layout = 'main';
        if (!$category = $this->categories->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $categories = Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft')->all();
        $dataProvider = Product::find()->where(['category_id' => $id])->all();
        $brands = Brand::find()->orderBy('name')->all();

        return $this->render('index', [
                    'brands' => $brands,
                    'categories' => $categories,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionBrand($id) {
        $this->layout = 'main';
        if (!$brand = $this->brands->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $categories = Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft')->all();
        $dataProvider = Product::find()->where(['brand_id' => $id])->all();
        $brands = Brand::find()->orderBy('name')->all();
        return $this->render('index', [
                    'brands' => $brands,
                    'categories' => $categories,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionTag($id) {
        if (!$tag = $this->tags->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $dataProvider = $this->products->getAllByTag($tag);

        return $this->render('tag', [
                    'tag' => $tag,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return mixed
     */
    public function actionSearch() {
        $form = new SearchForm();
        $form->load(\Yii::$app->request->queryParams);
        $form->validate();

        $dataProvider = $this->products->search($form);

        return $this->render('search', [
                    'dataProvider' => $dataProvider,
                    'searchForm' => $form,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionProduct($id) {
        if (!$product = $this->products->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $this->layout = 'blank';

        $cartForm = new AddToCartForm($product);
        $reviewForm = new ReviewForm();

        return $this->render('product', [
                    'product' => $product,
                    'cartForm' => $cartForm,
                    'reviewForm' => $reviewForm,
        ]);
    }

}
