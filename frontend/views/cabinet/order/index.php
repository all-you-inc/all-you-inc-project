<?php

use shop\entities\Shop\Order\Order;
use shop\helpers\OrderHelper;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Orders';
$this->params['breadcrumbs'][] = ['label' => 'Cabinet', 'url' => ['cabinet/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="user-index">

        <div class="box" style="margin-top:100px;margin-bottom:100px;">
            <h1><span class="glyphicon glyphicon-shopping-cart"></span>My Orders</h1>
            <div class="box-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'attribute' => 'Order id',
                            'value' => function (Order $model) {
                                return Html::a(Html::encode($model->id), ['view', 'id' => $model->id]);
                            },
                            'format' => 'raw',
                        ],
                        'created_at:datetime',
                        [
                            'attribute' => 'status',
                            'value' => function (Order $model) {
                                return OrderHelper::statusLabel($model->current_status);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'Action',
                            'value' => function (Order $model) {
                                return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['view', 'id' => $model->id]);
                            },
                            'format' => 'raw',
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>