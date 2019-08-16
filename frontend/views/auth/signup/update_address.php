<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\SignupForm */

use yii\helpers\Html;
use kartik\form\ActiveForm;

$this->title = 'Update Address';
$this->params['breadcrumbs'][] = $this->title;
//dd($industries);
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
        <!--<div class="row">-->
        <div class="center col-md-5">
            <div class="heading">
                <h2 class="title"><?= $this->title ?></h2>
            </div>
            <?php
            echo $this->render('_address', array('model' => $model, 'countries' => $countries));
            ?>
        </div>
        <!--</div>-->
    </div>
    <div class="mb-5"></div><!-- margin -->
</main><!-- End .main -->