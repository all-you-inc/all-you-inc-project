<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\widgets\Shop\CartWidget;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Category;

//dd($active);
?> 

<?php if(\Yii::$app->user->identity->getUser()->canUpdateProfile()){ ?>
    <div class="sidebar col-lg-12">
        <div class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Info!</strong> Please update your profile.
        </div>
    </div>
<?php } ?>
<?php if(\Yii::$app->user->identity->getUser()->hasAddress()){ ?>
    <div class="sidebar col-lg-12">
        <div class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Info!</strong> Please Add your Shipping Address.
        </div>
    </div>
<?php } ?>
<aside class="sidebar col-lg-3">
    <div class="widget widget-dashboard">
        <div class="sidemenu-intro">
            <img src="<?= Yii::getAlias('@web/images/spotlight/profile-1.jpg') ?>" alt="profile-image" class="sidemenu-profile-img"/>        
            <span class="sidemenu-profile-name"><?= \Yii::$app->user->identity->getName() ?></span>
            <?php if(\Yii::$app->user->identity->getUser()->userTalent->industry->name != NULl) { ?>
                <span class="sidemenu-profile-profession">Industry : <?= \Yii::$app->user->identity->getUser()->userTalent->industry->name ?></span>
            <?php } ?>
            <?php if(\Yii::$app->user->identity->getUser()->userTalent->talent->name != NULL) { ?>
                <span class="sidemenu-profile-profession">Talent : <?= \Yii::$app->user->identity->getUser()->userTalent->talent->name ?></span>
            <?php } ?>
            <span class="sidemenu-profile-country"><?= \Yii::$app->user->identity->getUser()->city ?> <?= \Yii::$app->user->identity->getUser()->state ?> <?= \Yii::$app->user->identity->getUser()->country ?></span>
        </div>
        <ul class="list">
            <li <?= $active == 'dashboard' ? 'class="active"' : '' ?> ><a href="<?= Html::encode(Url::to(['/dashboard'])) ?>">Dashboard</a></li>
            <li <?= $active == 'profile' ? 'class="active"' : '' ?> ><a href="<?= Html::encode(Url::to(['/profile'])) ?>">Profile</a></li>
            <li <?= $active == 'notification' ? 'class="active"' : '' ?> ><a href="#">Notification</a></li>
            <li <?= $active == 'products' ? 'class="active"' : '' ?> ><a href="<?= Html::encode(Url::to(['/products'])) ?>">Products</a></li>
            <li <?= $active == 'orders' ? 'class="active"' : '' ?> ><a href="<?= Html::encode(Url::to(['/orders'])) ?>">My Orders</a></li>
            <li <?= $active == 'auditions' ? 'class="active"' : '' ?> ><a href="#">Auditions</a></li>
            <li <?= $active == 'events' ? 'class="active"' : '' ?> ><a href="#">Events</a></li>
            <li <?= $active == 'fans' ? 'class="active"' : '' ?> ><a href="#">Fans</a></li>
            <li <?= $active == 'sales' ? 'class="active"' : '' ?> ><a href="<?= Html::encode(Url::to(['/sales'])) ?>">My Sales</a></li>
            <li <?= $active == 'earn_permoting' ? 'class="active"' : '' ?> ><a href="#">Earn Promoting</a></li>
            <li <?= $active == 'add_talent' ? 'class="active"' : '' ?> ><a href="#">Add Talent</a></li>
            <li <?= $active == 'information' ? 'class="active"' : '' ?> ><a href="#">Information</a></li>
        </ul>
    </div><!-- End .widget -->
</aside>