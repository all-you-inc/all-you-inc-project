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
use common\services\UserReferralService;
use yii\data\Pagination;
use common\models\usertalent\UserTalent;
use common\models\industry\Industry;
use common\models\talentmaster\TalentMaster;

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
        $dataProvider = Product::find()->where(['status' => 1, 'is_locked' => 0]);
        $categories = Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft')->all();
        $brands = Brand::find()->orderBy('name')->all();

        $countQuery = clone $dataProvider;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->defaultPageSize = 9;
        $dataProvider = $dataProvider->offset($pages->offset)
                ->limit($pages->limit)
                ->all();

        return $this->render('index', [
                    'brands' => $brands,
                    'categories' => $categories,
                    'dataProvider' => $dataProvider,
                    'pages' => $pages,
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
        $dataProvider = Product::find()->where(['category_id' => $id]);
        $brands = Brand::find()->orderBy('name')->all();

        $countQuery = clone $dataProvider;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->defaultPageSize = 9;
        $dataProvider = $dataProvider->offset($pages->offset)
                ->limit($pages->limit)
                ->all();

        return $this->render('index', [
                    'brands' => $brands,
                    'categories' => $categories,
                    'dataProvider' => $dataProvider,
                    'pages' => $pages,
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
        $dataProvider = Product::find()->where(['brand_id' => $id]);
        $brands = Brand::find()->orderBy('name')->all();

        $countQuery = clone $dataProvider;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->defaultPageSize = 9;
        $dataProvider = $dataProvider->offset($pages->offset)
                ->limit($pages->limit)
                ->all();

        return $this->render('index', [
                    'brands' => $brands,
                    'categories' => $categories,
                    'dataProvider' => $dataProvider,
                    'pages' => $pages,
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

    public function actionSearchProductBy() {
        $this->layout = 'main';
        $condition = '';
        $dataObj = \Yii::$app->request->get();
        $searchName = $dataObj['query'];
        $category = $dataObj['category'];
        if ($category != 'product') {
            $dataProvider = UserTalent::find();
            if ($searchName != null) {
                $dataProvider = $dataProvider->leftJoin('industry', 'industry.`id` = user_talent.`industry_id`');
                $dataProvider = $dataProvider->leftJoin('talent_master', 'talent_master.`id` = user_talent.`talent_id`');
                $condition .= 'industry.name LIKE (\'%' . $searchName . '%\') OR talent_master.name LIKE (\'%' . $searchName . '%\')';
                $dataProvider = $dataProvider->where($condition);
            }
            $countQuery = clone $dataProvider;
            $pages = new Pagination(['totalCount' => $countQuery->count()]);
            $pages->defaultPageSize = 8;
            $dataProvider = $dataProvider->offset($pages->offset)->limit($pages->limit)->all();

            return $this->render('talent', [
                        'dataProvider' => $dataProvider,
                        'pages' => $pages,
            ]);
        } else {
            $dataProvider = Product::find();
            if ($searchName != null) {
                $condition .= 'name LIKE (\'%' . $searchName . '%\')';
                $dataProvider = $dataProvider->where($condition);
            }
            $countQuery = clone $dataProvider;
            $pages = new Pagination(['totalCount' => $countQuery->count()]);
            $pages->defaultPageSize = 9;
            $dataProvider = $dataProvider->offset($pages->offset)->limit($pages->limit)->all();
            return $this->render('index', [
                        'brands' => Brand::find()->orderBy('name')->all(),
                        'categories' => Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft')->all(),
                        'dataProvider' => $dataProvider,
                        'pages' => $pages,
            ]);
        }
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
        $this->layout = 'main';
        if (isset($_GET['referral']) && $_GET['referral'] != NULL) {
            UserReferralService::addReferralInSession($_GET['referral'], $id);
        }
        $cartForm = new AddToCartForm($product);
        $reviewForm = new ReviewForm();

        return $this->render('product', [
                    'product' => $product,
                    'cartForm' => $cartForm,
                    'reviewForm' => $reviewForm,
        ]);
    }

}
