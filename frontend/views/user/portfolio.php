<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $category shop\entities\Shop\Category */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\widgets\Shop\FeaturedProductsWidget;
use frontend\widgets\Shop\FeaturedPortfolioProducts;
use frontend\widgets\Shop\PortfolioProducts;

$this->title = 'Portfolio';
$this->params['breadcrumbs'][] = $this->title;
//d($talent->user->name);
//dd($talent->user->country);
?>
<main class="main">
    <div class="banner banner-cat" style="background-image: url('<?= Yii::getAlias('@web/images/banners/portfolio.jpg') ?>');position: static;">
        <div>
            <img src="<?= Yii::getAlias('@web/images/spotlight/profile-1.jpg') ?>" alt="profile-image" class="portfolio-profile-img"/>        
        </div>
    </div>
    <div class="container">
        <div class="portfolio-intro">
            <span class="port-intro-name"><?= Html::encode($talent->user->name) ?></span>
            <span class="country"><?= Html::encode($talent->user->country) ?></span><br><br>
            <span class="port-intro-talent">J.K ROWLING</span>
            <span class="rating">Ratings: 
                <span class='portfolio-filled-star portfolio-star'>&#9733;</span>
                <span class='portfolio-filled-star portfolio-star'>&#9733;</span>
                <span class='portfolio-filled-star portfolio-star'>&#9733;</span>
                <span class='portfolio-filled-star portfolio-star'>&#9733;</span>
                <span class='portfolio-star'>&#9733;</span>
            </span><br><br>
            <button class="btn fan-btn">Become A Fan</button>
        </div>
        <div class="row">
            <?= FeaturedPortfolioProducts::widget(['limit' => 4]) ?>
        </div>
        <div class="row">
            <?= PortfolioProducts::widget() ?>
        </div>
        <hr class="portfolio-hr">
        <div class="portfolio-video">
            <div class="container">
                <div class="title-group text-center">
                    <h2 class="subtitle" style="color: aliceblue;margin: 20px;">VIDEO COLLECTION</h2>
                </div>
                <div id="portfolio-video" class="featured-products owl-carousel owl-theme">
                    <?php foreach ($products as $product): ?>
                        <figure class="product-image-container">
                            <?php if ($product->mainPhoto): ?>
                                <a href="<?= Html::encode(Url::to(['/shop/catalog/product', 'id' => $product->id])) ?>" class="product-image">
                                    <img src="<?= Html::encode($product->mainPhoto->getThumbFileUrl('file', 'catalog_list')) ?>" alt="product">
                                </a>
                            <?php endif; ?>
                        </figure>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- End .main -->
