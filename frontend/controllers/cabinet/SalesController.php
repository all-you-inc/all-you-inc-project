<?php

namespace frontend\controllers\cabinet;

use shop\readModels\Shop\SalesReadRepository;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class SalesController extends Controller
{
    public $layout = 'cabinet';
    private $sales;

    public function __construct($id, $module, SalesReadRepository $sales, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->sales = $sales;
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
    public function actionIndex()
    {
        $this->layout = 'main';
        $dataProvider = $this->sales->getSwm(\Yii::$app->user->id);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $this->layout = 'main';
        if (!$sales = $this->sales->findSwn($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('view', [
            'order' => $sales,
        ]);
    }
}