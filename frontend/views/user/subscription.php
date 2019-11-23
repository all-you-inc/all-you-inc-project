<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use common\models\usertalent\UserTalent;
use common\models\djgenre\DjGenre;

$this->title = 'Subscription';
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
        <?= $this->render('../layouts/shared/admin_menu', ['active' => 'subscription', 'url' => Html::encode(Url::to(['/myprofile']))]) ?>
        <div class="col-lg-9 order-lg-last dashboard-content">
            <h2><?= $this->title ?></h2>
            <?= Html::beginForm() ?>
            <div class="row">
                <?php if ($free) {
                    ?>
                    <div class="container">
                        <h2><input type="checkbox" id="checkAllBasic" name="free" value="<?= !isset($free[0]->membership->id) ? : $free[0]->membership->id ?>"> DOWNGRADE MEMBERSHIP TO FREE</h2>
                        <table class="table table-step-shipping">
                            <tbody> 
                                <?php foreach ($free as $item) { ?>
                                    <tr>
                                        <td><input type="checkbox" disabled="" class="basic"></td>
                                        <td><strong><?= $item->unit != 0 ? $item->unit . ' ' : 'NO ' ?><?= $item->itemType->title ?></strong></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } if ($basic) { ?>
                    <div class="container">
                        <h2><input type="checkbox" id="checkAllBasic" name="basic" value="<?= !isset($basic[0]->id) ? : $basic[0]->id ?>"> UPGRADE MEMBERSHIP @ <?= !isset($basic[0]->price) ? : $basic[0]->price . ' ' . !isset($basic[0]->currency->title) ? : $basic[0]->currency->title ?></h2>
                        <table class="table table-step-shipping">
                            <tbody> 
                                <?php foreach ($basic as $item) { ?>
                                    <tr>
                                        <td><input type="checkbox" disabled="" class="basic"></td>
                                        <td><strong><?= $item->unit != 0 ? $item->unit . ' ' : '' ?><?= $item->itemType->title ?></strong></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } if ($addons) { ?>
                    <div class="container">
                        <h2>Addons</h2>
                        <table class="table table-step-shipping">
                            <tbody> 
                                <?php foreach ($addons as $item) { ?>
                                    <tr>
                                        <td><input type="checkbox" name="addons[]" value="<?= $item->id ?>"></td>
                                        <td><strong><?= $item->unit != 0 ? $item->unit . ' ' : '' ?><?= $item->itemType->title ?></strong></td>
                                        <td>@ <?= $item->price . ' ' . $item->currency->title ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php }
                // if (isset($membership)) { ?>
                    <!-- <div class="container">
                        <h2>Membership</h2>
                        <table class="table table-step-shipping">
                            <tbody> 
                                <?php // foreach ($membership as $item) { ?>
                                    <tr>
                                        <td><input type="checkbox"   name="membership[]" value="<?php //echo $item->id ?>"></td>
                                        <td><strong><?php //echo $item->unit != 0 ? $item->unit . ' ' : '' ?><?php //echo $item->itemType->title ?></strong></td>
                                        <td>@ <?php //echo $item->price . ' ' . $item->currency->title ?></td>
                                    </tr>
                                <?php // } ?>
                            </tbody>
                        </table>
                    </div> -->
                <?php //} ?>
            </div><!-- End .row -->
            <?php
            if ($basic || $addons) {
                ?>
                <div class="form-footer">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'subscription-button']) ?>
                </div>
            <?php }else{ ?>
                No Subscription Found!!
            <?php } ?>
<?= Html::endForm() ?>
        </div>
    </div>
</main><!-- End .main -->
<script>
    $("#checkAllBasic").click(function () {
        $('.basic').not(this).prop('checked', this.checked);
    });
</script>