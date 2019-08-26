<?php

use common\models\usertalent\UserTalent;
use yii\helpers\Html;
use yii\helpers\Url;

$spotlight = UserTalent::find()->limit(4)->orderBy('id DESC')->all();
?>
<div class="container-fluid spotlight-main">
    <center>
        <img src="<?= Yii::getAlias('@web/images/spotlight.jpg') ?>" alt="spotlight">
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
                                    <img src="<?= Yii::getAlias('@web/images/spotlight/banner-1.jpg') ?>" alt="profile" class="background"/>
                                    <img src="<?= Yii::getAlias('@web/images/spotlight/profile-1.jpg') ?>" alt="profile-image" class="profile"/>
                                </div>
                                <div class="card-content">
                                    <h2><?= $item->user->name ?></h2>
                                    <h3 class="fan">Fans: 1.99M</h3>
                                    <div>
                                        <h4>From: <?= $item->user->country ?></h4>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </a>
                <?php
                }
            }
            ?>
        </div>
    </center>
</div><!-- End .container-fluid -->
