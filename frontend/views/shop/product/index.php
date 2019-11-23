<?php

use shop\entities\Shop\Product\Product;
use shop\helpers\PriceHelper;
use shop\helpers\ProductHelper;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Shop\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container-fluid">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= \yii\helpers\Url::home() ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
            </ol>
        </div><!-- End .container-fluid -->
    </nav>
    <div class="container">
    <?= $this->render('../../layouts/shared/admin_menu', ['active' => 'products']) ?>
        <div class="col-lg-9 order-lg-last dashboard-content">
            <h2><?= $this->title ?>         <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success', 'style' => 'float:right' ]) ?></h2>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
//                'filterModel' => $searchModel,
                'rowOptions' => function (Product $model) {
                    return $model->quantity <= 0 ? ['style' => 'background: #fdc'] : [];
                },
                        'columns' => [
                            [
                                 'attribute' => 'Photo',
                                'value' => function (Product $model) {
                                    return $model->mainPhoto ? Html::img($model->mainPhoto->getThumbFileUrl('file', 'admin')) : null;
                                },
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'width: 100px'],
                            ],
                            'id',
                            [
                                'attribute' => 'name',
                                'value' => function (Product $model) {
                                    return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                                },
                                        'format' => 'raw',
                                    ],
                                    [
                                        'attribute' => 'category_id',
                                        'filter' => $searchModel->categoriesList(),
                                        'value' => 'category.name',
                                    ],
                                    [
                                        'attribute' => 'price_new',
                                        'value' => function (Product $model) {
                                            return PriceHelper::format($model->price_new);
                                        },
                                    ],
                                    'quantity',
                                    [
                                        'attribute' => 'status',
                                        'filter' => $searchModel->statusList(),
                                        'value' => function (Product $model) {
                                            return ProductHelper::statusLabel($model->status);
                                        },
                                        'format' => 'raw',
                                    ],
                                    [
                                        'attribute' => 'locked status',
                                        'filter' => $searchModel->statusList(),
                                        'value' => function (Product $model) {
                                            return ProductHelper::lockStatus($model->is_locked);
                                        },
                                        'format' => 'raw',
                                    ],
                                ],
                            ]);
                            ?>
        </div>
    </div>
</main>