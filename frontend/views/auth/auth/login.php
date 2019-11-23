<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use common\widgets\Alert;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container-fluid">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= \yii\helpers\Url::home() ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Login</li>
            </ol>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="center_">
                <div class="heading">
                    <h2 class="title">Login</h2>
                </div>
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <label>Email</label>
                <?php echo $form->field($model, 'username')->textInput()->label(false) ?>
                <label>Password</label>
                <?php echo $form->field($model, 'password')->passwordInput()->label(false) ?>
                <?=
                $form->field($model, 'reCaptcha')->widget(
                        \himiklab\yii2\recaptcha\ReCaptcha::className(), ['siteKey' => \Yii::$app->params['reCaptcha']['site-key']]
                )
                ?>
                <a href="<?= Html::encode(Url::to(['auth/reset/request'])) ?>" class="forget-pass"> Forgot your password?</a>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary">LOGIN</button>
                    <?php ActiveForm::end(); ?>
                    <strong>OR</strong> 
                    <div class="login-signup-btn-padding">
                        <a href="<?= Html::encode(Url::to(['auth/signup/request'])) ?>" class="paction product-promote-btn login-signup-btn" title="SignUp">SignUp Now</a>
                    </div>
                </div>
            </div><!-- End .col-md-6 -->
        </div><!-- End .row -->
    </div><!-- End .container -->

    <div class="mb-5"></div><!-- margin -->
</main><!-- End .main -->
