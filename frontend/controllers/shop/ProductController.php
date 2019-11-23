<?php

namespace frontend\controllers\shop;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use shop\entities\Shop\Product\Product;
use backend\forms\Shop\ProductSearch;
use shop\forms\manage\Shop\Product\ProductCreateForm;
use shop\useCases\manage\Shop\ProductManageService;
use shop\entities\Shop\Product\Modification;
use shop\forms\manage\Shop\Product\QuantityForm;
use shop\forms\manage\Shop\Product\PhotosForm;
use shop\forms\manage\Shop\Product\PriceForm;
use shop\forms\manage\Shop\Product\ProductEditForm;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use shop\entities\Shop\Product\Value;
use common\services\UserAccessService;
use shop\entities\Shop\Product\Photo;

class ProductController extends Controller {

    private $service;

    public function __construct($id, $module, ProductManageService $service, $config = []) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors(): array {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'activate' => ['POST'],
                    'draft' => ['POST'],
                    'delete-photo' => ['POST'],
                    'move-photo-up' => ['POST'],
                    'move-photo-down' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $this->layout = 'main';
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $access = UserAccessService::checkByKey(\Yii::$app->user->identity->getUser(), 'PRODUCTS');
        if ($access) {
            $limit = UserAccessService::checkLimitByMsItem(\Yii::$app->user->identity->getUser(), $access, 'PRODUCTS');
            if ($limit) {
                $form = new ProductCreateForm(ProductCreateForm::FRONTEND_SETUP);
                if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                    if ($form->photos->files) {
                        try {
                            $product = $this->service->create($form);
                            return $this->redirect(['view', 'id' => $product->id]);
                        } catch (\DomainException $e) {
                            Yii::$app->errorHandler->logException($e);
                            Yii::$app->session->setFlash('error', $e->getMessage());
                        }
                    } else {
                        Yii::$app->session->setFlash('error', 'Please upload photo.');
                    }
                }
                return $this->render('create', [
                            'model' => $form,
                ]);
            } else {
                Yii::$app->session->setFlash('error', 'Limit end in your current plan, Please subscribe Addons for more.');
                return $this->redirect(['/products']);
            }
        } else {
            Yii::$app->session->setFlash('error', 'Access denied in your current plan, Please update your membership plan.<br><a href="' . Html::encode(Url::to(['/subscription'])) . '">Click Here</a>');
            return $this->redirect(['/products']);
        }
    }

    public function actionView($id) {
        $product = $this->findModel($id);

        $modificationsProvider = new ActiveDataProvider([
            'query' => $product->getModifications()->orderBy('name'),
            'key' => function (Modification $modification) use ($product) {
                return [
                    'product_id' => $product->id,
                    'id' => $modification->id,
                ];
            },
                    'pagination' => false,
                ]);

                $photosForm = new PhotosForm();
                if ($photosForm->load(Yii::$app->request->post()) && $photosForm->validate()) {
                    try {
                        $this->service->addPhotos($product->id, $photosForm);
                        return $this->redirect(['view', 'id' => $product->id]);
                    } catch (\DomainException $e) {
                        Yii::$app->errorHandler->logException($e);
                        Yii::$app->session->setFlash('error', $e->getMessage());
                    }
                }

                return $this->render('view', [
                            'product' => $product,
                            'modificationsProvider' => $modificationsProvider,
                            'photosForm' => $photosForm,
                ]);
            }

            public function actionDeletevalue($id) {
                try {
                    $value = Value::find()->where(['id' => $id])->one();
                    $value->delete();
                    $this->redirect(Yii::$app->request->referrer);
                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }

            public function actionPrice($id) {
                $product = $this->findModel($id);

                $form = new PriceForm($product);
                if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                    try {
                        $this->service->changePrice($product->id, $form);
                        return $this->redirect(['view', 'id' => $product->id]);
                    } catch (\DomainException $e) {
                        Yii::$app->errorHandler->logException($e);
                        Yii::$app->session->setFlash('error', $e->getMessage());
                    }
                }
                return $this->render('price', [
                            'model' => $form,
                            'product' => $product,
                ]);
            }

            /**
             * @param integer $id
             * @return mixed
             */
            public function actionQuantity($id) {
                $product = $this->findModel($id);

                $form = new QuantityForm($product);
                if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                    try {
                        $this->service->changeQuantity($product->id, $form);
                        return $this->redirect(['view', 'id' => $product->id]);
                    } catch (\DomainException $e) {
                        Yii::$app->errorHandler->logException($e);
                        Yii::$app->session->setFlash('error', $e->getMessage());
                    }
                }
                return $this->render('quantity', [
                            'model' => $form,
                            'product' => $product,
                ]);
            }

            public function actionDelete($id) {
                try {
                    $this->service->remove($id);
                } catch (\DomainException $e) {
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
                return $this->redirect(['index']);
            }

            public function actionUpdate($id) {
                $product = $this->findModel($id);

                $form = new ProductEditForm($product, ProductCreateForm::FRONTEND_SETUP);
                if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                    try {
                        $this->service->edit($product->id, $form);
                        return $this->redirect(['view', 'id' => $product->id]);
                    } catch (\DomainException $e) {
                        Yii::$app->errorHandler->logException($e);
                        Yii::$app->session->setFlash('error', $e->getMessage());
                    }
                }
                return $this->render('update', [
                            'model' => $form,
                            'product' => $product,
                ]);
            }

            /**
             * @param integer $id
             * @return mixed
             */
            public function actionActivate($id) {
                try {
                    $this->service->activate($id);
                } catch (\DomainException $e) {
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
                return $this->redirect(['view', 'id' => $id]);
            }

            /**
             * @param integer $id
             * @return mixed
             */
            public function actionDraft($id) {
                try {
                    $this->service->draft($id);
                } catch (\DomainException $e) {
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
                return $this->redirect(['view', 'id' => $id]);
            }

            /**
             * @param integer $id
             * @param $photo_id
             * @return mixed
             */
            public function actionDeletePhoto($id, $photo_id) {
                try {
                    $count = Photo::find()->where(['product_id'=>$id])->count();
                    if($count > 1){
                        $this->service->removePhoto($id, $photo_id);                        
                    } else {
                        Yii::$app->session->setFlash('error', 'You can not delete all photos');
                    }
                } catch (\DomainException $e) {
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
                return $this->redirect(['view', 'id' => $id, '#' => 'photos']);
            }

            /**
             * @param integer $id
             * @param $photo_id
             * @return mixed
             */
            public function actionMovePhotoUp($id, $photo_id) {
                $this->service->movePhotoUp($id, $photo_id);
                return $this->redirect(['view', 'id' => $id, '#' => 'photos']);
            }

            /**
             * @param integer $id
             * @param $photo_id
             * @return mixed
             */
            public function actionMovePhotoDown($id, $photo_id) {
                $this->service->movePhotoDown($id, $photo_id);
                return $this->redirect(['view', 'id' => $id, '#' => 'photos']);
                }

                protected function findModel($id): Product
                {
                if (($model = Product::find()->where(['id' => $id, 'created_by' => \Yii::$app->user->id])->one()) !== null) {
                return $model;
                }
                throw new NotFoundHttpException('The requested page does not exist.');
            }

        }
        