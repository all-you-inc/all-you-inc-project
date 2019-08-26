<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\widgets\Shop\CartWidget;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Category;

if (\yii\helpers\Url::home() == Yii::$app->request->url) {
    $class = 'header header-transparent';
} else {
    $class = 'header custom-yellow-header';
}
?> 

<header class="<?= $class ?>">
    <div class="header-middle sticky-header">
        <div class="container-fluid">
            <div class="header-left">
                <?php if (Yii::$app->user->isGuest): ?>
                    <a href="<?= Html::encode(Url::to(['/signup'])) ?>" class="btn signup-btn">Sign Up</a>
                <?php endif; ?>
                <nav class="main-nav">
                    <ul class="menu sf-arrows">
                        <!-----PRODUCTS----->

                        <li>
                            <a href="<?= Html::encode(Url::to(['/shop/catalog/index'])) ?>" class="sf-with-ul custom-home-btn">PRODUCTS</a>
                            <div class="megamenu megamenu-fixed-width">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <?php
                                            $categories = Category::find()->where(['is_dashboard' => 1])->limit(3)->orderBy('id ASC')->all();
                                            if ($categories) {
                                                foreach ($categories as $category) {
                                                    ?>
                                                    <div class="col-lg-6">
                                                        <div class="menu-title">
                                                            <a href="#"><?= $category->name ?>
                                                            </a>
                                                        </div>
                                                        <ul>
                                                            <?php
                                                            $products = Product::find()->where(['category_id' => $category->id, 'status' => 1])->orderBy('id DESC')->all();
                                                            if ($products) {
                                                                foreach ($products as $product) {
                                                                    ?>
                                                                    <li><a href="<?= Url::to(['/shop/catalog/product', 'id' => $product->id]); ?>"><?= $product->name ?>
                                                                        </a></li>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div><!-- End .col-lg-6 -->
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div><!-- End .row -->
                                    </div><!-- End .col-lg-8 -->
                                    <div class="col-lg-4">
                                        <div class="banner">
                                            <a href="#">
                                                <img src="<?= Yii::getAlias('@web/images/menu-banner-2.jpg') ?>" alt="Menu banner">
                                            </a>
                                        </div><!-- End .banner -->
                                    </div><!-- End .col-lg-4 -->
                                </div>
                            </div><!-- End .megamenu -->
                        </li>
                        <!-----EVENTS----->
                        <li>
                            <a href="#" class="sf-with-ul custom-home-btn">EVENTS</a>

                            <ul>
                                <li><a href="">Event 1</a></li>
                                <li><a href="">Event 2</a></li>
                            </ul>
                        </li>
                        <!-----SHOPS----->
                        <li class="megamenu-container">
                            <a href="" class="sf-with-ul custom-home-btn">SHOPS</a>
                            <div class="megamenu">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="menu-title">
                                                    <a href="#">Category 1</a>
                                                </div>
                                                <ul>
                                                    <li><a href="">Item 1.1</a></li>
                                                    <li><a href="">Item 1.2</a></li>
                                                    <li><a href="">Item 1.3</a></li>
                                                </ul>
                                            </div><!-- End .col-lg-4 -->
                                            <div class="col-lg-4">
                                                <div class="menu-title">
                                                    <a href="#">Category 2</a>
                                                </div>
                                                <ul>
                                                    <li><a href="">Item 2.1</a></li>
                                                    <li><a href="">Item 2.2</a></li>
                                                    <li><a href="">Item 2.3</a></li>
                                                    <li><a href="">Item 2.4</a></li>
                                                </ul>
                                            </div><!-- End .col-lg-4 -->
                                            <div class="col-lg-4">
                                                <div class="menu-title">
                                                    <a href="#">Category 3</a>
                                                </div>
                                                <ul>
                                                    <li><a href="">Item 3.1</a></li>
                                                    <li><a href="">Item 3.2</a></li>
                                                    <li><a href="">Item 3.3</a></li>
                                                    <li><a href="">Item 3.4</a></li>
                                                    <li><a href="">Item 3.5</a></li>
                                                </ul>
                                            </div><!-- End .col-lg-4 -->
                                        </div><!-- End .row -->
                                    </div><!-- End .col-lg-8 -->
                                    <div class="col-lg-4">
                                        <div class="banner">
                                            <a href="#">
                                                <img src="<?= Yii::getAlias('@web/images/menu-banner.jpg') ?>" alt="Menu banner" class="product-promo">
                                            </a>
                                        </div><!-- End .banner -->
                                    </div><!-- End .col-lg-4 -->
                                </div><!-- End .row -->
                            </div>
                            <!-- End .megamenu -->
                        </li>
                    </ul>
                </nav>
            </div><!-- End .header-left -->

            <div class="header-center">
                <a href="<?= \yii\helpers\Url::home() ?>" class="logo">
                    <img src="<?= Yii::getAlias('@web/images/logo.png') ?>" alt="All You Media Logo">
                </a>
            </div><!-- End .header-center -->

            <div class="header-right">
                <nav class="main-nav">
                    <ul class="menu sf-arrows">
                        <!-----EVENTS----->
                        <li>
                            <a href="#" class="sf-with-ul custom-home-btn">My Account</a>

                            <ul>
                                <?php if (Yii::$app->user->isGuest): ?>
                                    <li><a class="menu" href="<?= Html::encode(Url::to(['/auth/auth/login'])) ?>">Login</a></li>
                                <?php else: ?>
                                    <li><a class="menu" href="<?= Html::encode(Url::to(['/dashboard'])) ?>">Dashboard</a></li>
                                    <li><a class="menu" href="<?= Html::encode(Url::to(['/auth/auth/logout'])) ?>" data-method="post">Logout</a></li>
                                <?php endif; ?>

                            </ul>
                        </li>
                    </ul>
                    </li>
                    </ul>
                </nav>
                <!---- cart view ---->             
                <?= CartWidget::widget() ?>


            </div><!-- End .header-dropdowns -->
        </div><!-- End .header-right -->
    </div><!-- End .container-fluid -->
</div><!-- End .header-middle -->
</header><!-- End .header -->
