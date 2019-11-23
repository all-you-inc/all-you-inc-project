<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = $response['request'] . 'Contact Us';
$this->params['breadcrumbs'][] = $this->title;
?>
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container-fluid">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
            </ol>
        </div><!-- End .container-fluid -->
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h2 class="light-title">Write <strong>Us</strong></h2>

                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                <div class="form-group required-field">
                    <label for="contact-name">Name</label>
                    <?= $form->field($model, 'name')->textInput(['autofocus' => true])->label(false) ?>
                </div><!-- End .form-group -->

                <div class="form-group required-field">
                    <label for="contact-email">Email</label>
                    <?= $form->field($model, 'email')->label(false) ?>
                </div><!-- End .form-group -->

                <div class="form-group">
                    <label for="contact-subject">Subject</label>
                    <?= $form->field($model, 'subject')->label(false) ?>
                </div><!-- End .form-group -->

                <div class="form-group required-field">
                    <label for="contact-message">What’s on your mind?</label>
                    <?= $form->field($model, 'body')->textarea(['rows' => 6])->label(false) ?>
                </div><!-- End .form-group -->

                <?=
                $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ])
                ?>
                <div class="form-footer">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div><!-- End .form-footer -->
                <?php ActiveForm::end(); ?>
            </div><!-- End .col-md-8 -->

            <div class="col-md-4">
                <h2 class="light-title">Contact <strong>Details</strong></h2>

                <div class="contact-info">
                    <div>
                        <i class="icon-phone"></i>
                        <p><a href="tel:">0201 203 2032</a></p>
                        <p><a href="tel:">0201 203 2032</a></p>
                    </div>
                    <div>
                        <i class="icon-mobile"></i>
                        <p><a href="tel:">201-123-3922</a></p>
                        <p><a href="tel:">302-123-3928</a></p>
                    </div>
                    <div>
                        <i class="icon-mail-alt"></i>
                        <p><a href="mailto:#">allyouinc@gmail.com</a></p>
                        <p><a href="mailto:#">support@allyouinc.com</a></p>
                    </div>
<!--                    <div>
                        <i class="icon-skype"></i>
                        <p>porto_skype</p>
                        <p>porto_template</p>
                    </div>-->
                </div><!-- End .contact-info -->
            </div><!-- End .col-md-4 -->
        </div><!-- End .row -->
    </div><!-- End .container -->

    <div class="mb-8"></div><!-- margin -->
</main><!-- End .main -->