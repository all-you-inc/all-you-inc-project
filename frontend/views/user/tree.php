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

$this->title = 'My Team';
$this->params['breadcrumbs'][] = $this->title;
$url = '/earn?referral=';
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
            <div class="row">
                <div class="col-md-12">
                    <?php if ($tree) { ?>
                        <div class="card">
                            <div class="card-header">
                                Referral Tree View
                            </div><!-- End .card-header -->
                            <div class="card-body">
                                <!----- Tree Start ----->
                                <ul id="treeUL">
                                    <li><!-- tier 1 start-->
                                        <span class="treebox">You</span>
                                        <ul class="treenested"><!--ul tier 1 start-->
                                            <?php
                                            if ($tree) {
                                                foreach ($tree as $item) {
                                                    $tier2 = $item;
                                                    break;
                                                }
                                            }
                                            ?>
                                            <?php
                                            if (isset($tier2)) {
                                                foreach ($tier2 as $key1 => $item) {
                                                    $break = explode(' -|+ ', $item);
                                                    $item = $break[0];
                                                    $referral = $break[1];
                                                    ?>
                                                    <li><!-- tier 2 start-->
                                                        <?php if (array_key_exists($key1, $tree)) { ?>
                                                        <span class="treebox"><a href="<?= Url::to([$url.$referral]) ?>"><?= $item ?></a></span>
                                                            <ul class="treenested"><!--ul tier 2 start-->
                                                                <?php
                                                                foreach ($tree as $key2 => $tier3) {
                                                                    if ($key2 == $key1) {
                                                                        foreach ($tier3 as $key3 => $item) {
                                                                            $break = explode(' -|+ ', $item);
                                                                            $item = $break[0];
                                                                            $referral = $break[1];
                                                                            ?>
                                                                            <li><!-- tier 3 start-->
                                                                                <?php if (array_key_exists($key3, $tree)) { ?>
                                                                                    <span class="treebox"><a href="<?= Url::to([$url.$referral]) ?>"><?= $item ?></a></span>
                                                                                    <ul class="treenested"><!--ul tier 3 start-->
                                                                                        <?php
                                                                                        foreach ($tree as $key4 => $tier4) {
                                                                                            if ($key4 == $key3) {
                                                                                                foreach ($tier4 as $key4 => $item) {
                                                                                                    $break = explode(' -|+ ', $item);
                                                                                                    $item = $break[0];
                                                                                                    $referral = $break[1];
                                                                                                    ?>
                                                                                                    <li><!-- tier 4 start-->
                                                                                                        <?php if (array_key_exists($key4, $tree)) { ?>
                                                                                                            <span class="treebox"><a href="<?= Url::to([$url.$referral]) ?>"><?= $item ?></a></span>
                                                                                                            <ul class="treenested"><!--ul tier 4 start-->
                                                                                                                <?php
                                                                                                                foreach ($tree as $key5 => $tier5) {
                                                                                                                    if ($key5 == $key4) {
                                                                                                                        foreach ($tier5 as $key4 => $item) {
                                                                                                                            $break = explode(' -|+ ', $item);
                                                                                                                            $item = $break[0];
                                                                                                                            $referral = $break[1];
                                                                                                                            ?>
                                                                                                                            <li><!-- tier 5 start-->
                                                                                                                                <a href="<?= Url::to([$url.$referral]) ?>"><span class="treecaret"><?= $item ?></span></a>
                                                                                                                            </li><!-- tier 5 end-->                                                                       <?php
                                                                                                                        }
                                                                                                                    }
                                                                                                                }
                                                                                                                ?>
                                                                                                            </ul><!--ul tier 4 end-->
                                                                                                        <?php } else { ?>
                                                                                                        <li><a href="<?= Url::to([$url.$referral]) ?>"><span class="treecaret"><?= $item ?></span></a></li>
                                                                                                    <?php } ?>  
                                                                                            </li><!-- tier 4 end-->
                                                                                            <?php
                                                                                        }
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </ul><!--ul tier 3 end-->
                                                                        <?php } else { ?>
                                                                        <li><a href="<?= Url::to([$url.$referral]) ?>"><span class="treecaret"><?= $item ?></span></a></li>
                                                                    <?php } ?>  
                                                            </li><!-- tier 3 end-->
                                                            <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </ul><!--ul tier 2 end-->
                                        <?php } else { ?>
                                            <li><a href="<?= Url::to([$url.$referral]) ?>"><span class="treecaret"><?= $item ?></span></a></li>
                                            <?php } ?>
                                        </li><!-- tier 2 end-->
                                        <?php
                                    }
                                }
                                ?>
                                </ul><!--ul tier 1 end-->
                                </li><!-- tier 1 end-->
                                </ul>
                                <!----- Tree END ----->
                            </div><!-- End .card-body -->
                        </div><!-- End .card -->
                        <?php
                    } else {
                        echo 'No Record Found!!';
                    }
                    ?>
                </div><!-- End .col-md-6 -->
            </div><!-- End .row -->
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