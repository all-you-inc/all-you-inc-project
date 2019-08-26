<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use common\models\usertalent\UserTalent;
use common\models\djgenre\DjGenre;

$this->title = 'Profile';
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
        <?= $this->render('../layouts/shared/admin_menu', ['active' => 'profile', 'url' => Html::encode(Url::to(['/myprofile']))]) ?>
        <div class="col-lg-9 order-lg-last dashboard-content">
            <h2><?= $this->title ?></h2>

            <h3>Account Information</h3>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Contact Information
                            <a href="<?= Html::encode(Url::to(['/updateprofile'])) ?>" class="card-edit">Edit</a>
                        </div><!-- End .card-header -->

                        <div class="card-body">
                            <p>
                                <?= $model->name ?>
                                <br><?= $model->email ?>
                                <?= $model->phone != null && $model->phone != '' ? '<br>' . $model->phone : '' ?>
                                <?= $model->city != null && $model->city != '' ? '<br>' . $model->city : '' ?>
                                <?= $model->state != null && $model->state != '' ? '<br>' . $model->state : '' ?>
                                <?= $model->country != null && $model->country != '' ? '<br>' . $model->country : '' ?>
                                <!--<a href="#">Change Password</a>-->
                            </p>
                        </div><!-- End .card-body -->
                    </div><!-- End .card -->
                </div><!-- End .col-md-6 -->
                    <?php if(\Yii::$app->user->identity->getUser()->canShowTalent()){ ?>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    Talent Information
                                    <?php if (\Yii::$app->user->identity->getUser()->canUpdateProfile()) { ?>
                                        <a href="<?= Html::encode(Url::to(['/signup/profile?auth_key='.\Yii::$app->user->identity->getUser()->auth_key])) ?>" class="card-edit">Add</a>
                                    <?php } ?>
                                </div><!-- End .card-header -->
                                <div class="card-body">
                                    <p>
                                        <?= isset($talent->industry->name) ? 'Industry:' . $talent->industry->name : '' ?>
                                        <?= isset($talent->talent->name) ? '<br>Talent:' . $talent->talent->name : '' ?>
                                        <?= isset($talent->djgenre->name) ? '<br>Dj Genre: ' . $talent->djgenre->name : '' ?>
                                        <?= isset($talent->instrument->name) ? '<br>Instrument: ' . $talent->instrument->name : '' ?>
                                        <?= isset($talent->instrumentspecification->name) ? '<br>Instrument Specification: ' . $talent->instrumentspecification->name : '' ?>
                                        <?= isset($talent->musicgenre->name) ? '<br>Music Genre: ' . $talent->musicgenre->name : '' ?>
                                    </p>
                                </div><!-- End .card-body -->
                            </div><!-- End .card -->
                        </div><!-- End .col-md-6 -->
                    <? } ?>
            </div><!-- End .row -->

            <div class="card">
                <div class="card-header">
                    Address Book
                    <a href="<?= Html::encode(Url::to(['/addaddress'])) ?>" class="card-edit">Add</a>
                </div><!-- End .card-header -->

                <div class="card-body">
                    <div class="row">
                        <?php
                        if ($user_addresses) {
                            foreach ($user_addresses as $user_address) {
                                ?>
                                <div class="col-md-6">
                                    <h4 class="font-weight"><?= $user_address->default == 1 ? 'Default ' : '' ?>Shipping Address</h4>
                                    <address style="margin-bottom: 30px;">
                                        <?= isset($user_address->address) ? '<br>' . $user_address->address : '' ?>
                                        <?= isset($user_address->area) ? '<br>' . $user_address->area : '' ?>
                                        <?= isset($user_address->postal_code) ? '<br>' . $user_address->postal_code : '' ?>
                                        <?= isset($user_address->city) ? '<br>' . $user_address->city : '' ?>
                                        <?= isset($user_address->state) ? '<br>' . $user_address->state : '' ?>
                                        <?= isset($user_address->country->title) ? '<br>' . $user_address->country->title : '' ?>
                                        <a href="<?= Html::encode(Url::to(['/updateaddress', 'id' => $user_address->id])) ?>">Edit Address</a>
                                    </address>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div><!-- End .card-body -->
            </div><!-- End .card -->
        </div>

    </div>
</main><!-- End .main -->