<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $category shop\entities\Shop\Category */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\widgets\Shop\FeaturedProductsWidget;
use frontend\widgets\Shop\FeaturedPortfolioProducts;
use frontend\widgets\Shop\PortfolioProducts;
use common\models\userprofileimage\UserProfileImage;

$this->title = 'Portfolio';
$this->params['breadcrumbs'][] = $this->title;
//d($talent->user->name);
//dd($talent->user->country);
?>
<main class="main">
    <?php $imageBanner = UserProfileImage::getBannerImage($talent->user->id); ?>
    <?php $image = UserProfileImage::getProfileImage($talent->user->id) ?>

    <?php if($talent->user->id == \Yii::$app->user->id) {?>
    <div class="container-fluid banner banner-cat" style="background-image: url('<?= ($imageBanner == null) ? Yii::getAlias('@web/images/banners/portfolio.jpg') : $imageBanner ?>');position: static;">
        <form id="profilefileupload" action="<?= yii\helpers\Url::to(['user/uploadprofile']);?>" method="POST" enctype="multipart/form-data"> 
            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
            <a href="#" onclick="javascript:$('#profilefileupload input').click();" class="show-on-custom-link" aria-label="Upload profile">
                <img src="<?= ($image == null) ? Yii::getAlias('@web/images/spotlight/profile-1.png') : $image ?>" alt="profile-image" class="portfolio-profile-img"/>    
            </a>
            <input type="file" name="PhotosForm[files]" id="filespro" style="display:none;">      
        </form>     
    </div>
    
    <div class="container">
        <form id="bannerfileupload" action="<?= yii\helpers\Url::to(['user/uploadbanner']);?>" method="POST" enctype="multipart/form-data"> 
            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
            <a href="#" onclick="javascript:$('#bannerfileupload input').click();" class="show-on-custom-link" aria-label="Upload banner">
                <div class="btn btn-primary">Upload Banner Image</div>   
            </a>
            <input type="file" name="PhotosForm[files]" id="filesban" style="display:none;">      
        </form> 
    </div>
    <?php }else{ ?>
        <div class="container-fluid banner banner-cat" style="background-image: url('<?= ($imageBanner == null) ? Yii::getAlias('@web/images/banners/portfolio.jpg') : $imageBanner ?>');position: static;">
            <div>
                <img src="<?= ($image == null) ? Yii::getAlias('@web/images/spotlight/profile-1.png') : $image ?>" alt="profile-image" class="portfolio-profile-img"/>     
            </div>
        </div>
    <? } ?>
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
<script>
$('#filespro').change(function() {
  // submit the form 
      $('#profilefileupload').submit();
  });

 $('#filesban').change(function() {
  // submit the form 
      $('#bannerfileupload').submit();
  });
</script>