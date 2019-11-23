<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use common\models\usertalent\UserTalent;
use common\models\djgenre\DjGenre;
use shop\entities\User\User;

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
                            Contact
                            <a href="<?= Html::encode(Url::to(['/updateprofile'])) ?>" class="card-edit">Edit</a>
                        </div><!-- End .card-header -->

                        <div class="card-body">
                            <p>
                                <?= $model->name ?>
                                <br><?= $model->email ?>
                                <?= $model->phone != null && $model->phone != '' ? '<br>' . $model->phone : '' ?>
                                <?= $model->city != null && $model->city != '' ? '<br>' . $model->city : '' ?>
                                <?= $model->state != null && $model->state != '' ? '<br>' . $model->state : '' ?>
                                <?= isset($model->userCountry->title) && $model->userCountry->title != null ? '<br>' . $model->userCountry->title : '' ?>
                                <!--<a href="#">Change Password</a>-->
                                 <br><strong>Gallery : </strong> <a href="<?= Html::encode(Url::to(['/gallery'])) ?>">View</a>
                            </p>
                        </div><!-- End .card-body -->
                    </div><!-- End .card -->
                </div><!-- End .col-md-6 -->

                <?php if (\Yii::$app->user->identity->getUser()->canShowBothTalent()) { ?>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                Talent
                                <a href="<?= Html::encode(Url::to(['/addtalent'])) ?>" class="card-edit">Add</a>
                            </div><!-- End .card-header -->
                            <?php if ($talents) { ?>
                                <div class="card-body">
                                    <?php
                                    foreach ($talents as $key => $talent) {
                                        ?>
                                        <h4 class="talent-number">Talent # <?= $key + 1 ?></h4>
                                        <p>
                                            <?= isset($talent->industry->name) ? 'Industry: ' . $talent->industry->name : '' ?>
                                            <?= isset($talent->talent->name) ? '<br>Talent: ' . $talent->talent->name : '' ?>
                                            <?= isset($talent->gender) && $talent->gender != '' ? '<br>Gender: ' . $talent->gender : '' ?>
                                            <?= isset($talent->djgenre->name) ? '<br>Dj Genre: ' . $talent->djgenre->name : '' ?>
                                            <?= isset($talent->instrument->name) ? '<br>Instrument: ' . $talent->instrument->name : '' ?>
                                            <?= isset($talent->instrumentspecification->name) ? '<br>Instrument Specification: ' . $talent->instrumentspecification->name : '' ?>
                                            <?= isset($talent->musicgenre->name) ? '<br>Music Genre: ' . $talent->musicgenre->name : '' ?>
                                        </p>
                                    <?php } ?>
                                </div><!-- End .card-body -->
                            <?php } ?>
                        </div><!-- End .card -->
                    </div><!-- End .col-md-6 -->
                <?php } ?>
                <?php
                if (\Yii::$app->user->identity->getUser()->isPromoter()) {
                    $shareUrl = Url::base('https') . Url::to(['/signup?referral=' . $model->referral_code]);
                    ?>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                Referral
                                <button class="float-right btn-primary" onclick="prompt('To Promote Press Ctrl + C, then Enter to copy to clipboard', '<?php echo $shareUrl ?>')">Promote</button>
                            </div><!-- End .card-header -->
                            <div class="card-body">
                                <strong>Link : </strong><a target="_blank" href="<?= $shareUrl ?>"><?= $shareUrl ?></a><br>
                                <strong>Tier Level : </strong><?= $referral['level'] ?><br>
                                <strong>Tier Count : </strong><?= $referral['count'] ?><br>
                                <strong>My Team : </strong> <a href="<?= Html::encode(Url::to(['/myteam'])) ?>">View</a>
                            </div><!-- End .card-body -->
                        </div><!-- End .card -->
                    </div><!-- End .col-md-6 -->
                <?php } ?>
            </div><!-- End .row -->

            <div class="card">
                <div class="card-header">
                    Address Book
                    <a href="<?= Html::encode(Url::to(['/addaddress'])) ?>" class="card-edit">Add Address</a>
                </div><!-- End .card-header -->
                <?php if ($user_addresses) { ?>
                    <div class="card-body">
                        <div class="row">
                            <?php
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
                            <?php } ?>
                        </div>
                    </div><!-- End .card-body -->
                <?php } ?>
            </div><!-- End .card -->
            <div class="card">
                <div class="card-header">
                    Membership Subscription
                    <a href="<?= Html::encode(Url::to(['/subscription'])) ?>" class="card-edit">Add Subscription</a>
                </div><!-- End .card-header -->
                <?php
                if ($subscriptions) {
                    ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h3> <?= ($subscriptions[0]->membership->title) ?> MEMBERSHIP</h3>
                                <p>
                                    <?php
                                    foreach ($subscriptions as $subscription) {
                                        if ($subscription->type == 'basic') {
                                            ?>
                                            <?= $subscription->itemType->title ?> 
                                            <strong><?= $subscription->unit == 0 ? '' : $subscription->unit ?></strong>
                                            <br>
                                            <?php
                                        }
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h3>ADDONS</h3>
                                <p>
                                    <?php
                                    foreach ($subscriptions as $subscription) {
                                        if ($subscription->type == 'addons') {
                                            ?>
                                            <?= $subscription->itemType->title ?> 
                                            <strong><?= $subscription->unit == 0 ? '' : $subscription->unit ?></strong>
                                            <br>
                                            <?php
                                        }
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div><!-- End .card-body -->
                <?php } ?>
            </div><!-- End .card -->
        </div>
    </div>
</main>
<script>
    var toggler = document.getElementsByClassName("treebox");
    var i;

    for (i = 0; i < toggler.length; i++) {
        toggler[i].addEventListener("click", function () {
            this.parentElement.querySelector(".treenested").classList.toggle("treeactive");
            this.classList.toggle("treecheck-box");
        });
    }
</script>