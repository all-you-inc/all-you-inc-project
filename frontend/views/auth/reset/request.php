<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container-fluid">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= \yii\helpers\Url::home() ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
            </ol>
        </div><!-- End .container-fluid -->
    </nav>
    <div class="container">
        <!--<div class="row">-->
        <div class="center">
            <div class="heading">
                <h2 class="title"><?= Html::encode($this->title) ?></h2>
            </div> 
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
            <div class="form-footer">
                <?= Html::submitButton('Send', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div> 
            <?= $form->errorSummary($model) ?>
            <?php ActiveForm::end(); ?>
        </div>
        <!--</div>-->
    </div>
    <div class="mb-5"></div><!-- margin -->
</main><!-- End .main -->
