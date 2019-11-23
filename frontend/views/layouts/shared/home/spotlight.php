<?php

use common\models\usertalent\UserTalent;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\userprofileimage\UserProfileImage;

$spotlight = UserTalent::find()->limit(4)->orderBy('id ASC')->all();
?>
<div class="container-fluid spotlight-main">
   
        <div class="container spotlight-header">
        <img src="<?= Yii::getAlias('@web/images/spotlight.png') ?>" alt="spotlight" class="align-center">
        
        </div>
        <div class="container">
            <?php
            if ($spotlight) {
                foreach ($spotlight as $item) {
                    $url = Url::to(['user/portfolio', 'id' => $item->id]);
                    ?>
                    <a href="<?= Html::encode($url) ?>">
                        <div class="col-md-3">
                            <div class="card profile-card-3">
                                <div class="background-block">
                                    <?php $imageprofile = UserProfileImage::getProfileImage($item->user->id); ?>
                                    <img src="<?=  $imageprofile == NULL ? 
                                    Yii::getAlias('@web/images/spotlight/profile-1.png') : 
                                    $imageprofile  ?>" 
                                    alt="profile-image" 
                                    class="spotlight-profile-img"/>
                                </div>
                                <div class="card-content">
                                    <h2><?= $item->user->name ?></h2>
                                    <h4 ><?=( $item->talent->name);?></h4>
                                    <h3 class="fan">Fans: 0</h3>
                                    <?php if(isset($item->user->userCountry->title)): ?>
                                    <div>
                                        <h4>From: <?= $item->user->userCountry->title ?></h4>
                                    </div>
                                    <?php endif; ?>    
                                </div>

                            </div>
                        </div>
                    </a>
                <?php
                }
            }
            ?>
        </div>
   
</div><!-- End .container-fluid -->
