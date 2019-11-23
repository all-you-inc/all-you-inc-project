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

$this->title = 'My Order';
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
<?= $this->render('../../layouts/shared/admin_menu', ['active' => 'orders', 'url' => Html::encode(Url::to(['/myprofile']))]) ?>
        <div class="col-lg-9 order-lg-last dashboard-content">
            <h2><?= $this->title ?></h2>
            <?=
            GridView::widget([
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
                            ]);
                            ?>
        </div>
    </div>
</main>