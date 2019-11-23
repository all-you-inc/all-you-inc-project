<?php
/* @var $this yii\web\View */
/* @var $user shop\entities\Shop\Product\Product */

use common\models\usertalent\UserTalent;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\userprofileimage\UserProfileImage;

//dd($talent->user);
$user = $talent->user;
$url = Url::to(['user/portfolio', 'id' => $talent->id]);
?>
<div class="col-6 col-md-4">
    <a href="<?= Html::encode($url) ?>">
        <div class="col-md-3">
            <div class="card profile-card-3">
                <div class="background-block">
                    <?php $imageprofile = UserProfileImage::getProfileImage($user->id); ?>
                    <?php $imageBanner = UserProfileImage::getBannerImage($user->id); ?>
                    <img src="<?= $imageBanner == NULL ? Yii::getAlias('@web/images/spotlight/banner.jpg') : $imageBanner ?>" alt="profile" class="background"/>
                    <img src="<?= $imageprofile == NULL ? Yii::getAlias('@web/images/spotlight/profile-1.png') : $imageprofile ?>" alt="profile-image" class="sidemenu-profile-img"/>
                </div>
                <div class="card-content">
                    <h2><?= $user->name ?></h2>
                    <h3 class="fan">Fans: 1.99M</h3>
                    <div>
                        <h4>From: <?= $user->country ?></h4>
                    </div>
                </div>

            </div>
        </div>
    </a>
</div><!-- End .col-md-4 -->
