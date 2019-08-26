<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use common\models\usertalent\UserTalent;
use shop\entities\Shop\Order\Order;
use shop\helpers\OrderHelper;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use shop\helpers\PriceHelper;
use yii\widgets\DetailView;

$this->title = 'Order No : ' . $order->id;
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
        <div class="col-lg-12 order-lg-last dashboard-content">
            <h2>
                <?=  'My Sales (' . $this->title . ')' ?>
                <button class="btn btn-primary" style="float: right;margin: -12px 0;" onclick="history.go(-1);">Back </button></h2>
            <?=
            DetailView::widget([
                'model' => $order,
                'attributes' => [
                    'id',
                    'created_at:datetime',
                    [
                        'attribute' => 'current_status',
                        'value' => OrderHelper::statusLabel($order->current_status),
                        'format' => 'raw',
                    ],
                    'delivery_method_name',
                    [
                        'attribute' => 'delivery_address',
                        'value' => $order->useraddress->address,
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'Cost',
                        'value' => $order->getSalesCost(),
                        'format' => 'raw',
                    ],
                    'note:ntext',
                ],
            ])
            ?>
            <div class="table-responsive" style="margin-top:20px;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-left">Product Name</th>
                            <th class="text-left">Quantity</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order->items as $item): ?>
                            <?php if($item->product->created_by == \Yii::$app->user->id){ ?>
                                <tr>
                                    <td class="text-left">
                                        <?= Html::encode($item->product_name) ?>
                                    </td>
                                    <td class="text-left">
                                        <?= $item->quantity ?>
                                    </td>
                                    <td class="text-right"><?= PriceHelper::format($item->price) ?></td>
                                    <td class="text-right"><?= PriceHelper::format($item->getCost()) ?></td>
                                </tr>
                            <?php } ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>