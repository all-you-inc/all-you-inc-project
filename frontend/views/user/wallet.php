<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Wallet';
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
        <?= $this->render('../layouts/shared/admin_menu', ['active' => 'wallet', 'url' => Html::encode(Url::to(['/wallet']))]) ?>
        <div class="col-lg-19 order-lg-last dashboard-content">
            <h2><?= $this->title ?></h2>
            <div class="row">
                <div class="container">
                    <?= $this->render('_wallet', ['funds' => $funds, 'total' => $total,'pages'=>$pages]) ?>

                </div>
            </div>
        </div>
    </div>
</main><!-- End .main -->