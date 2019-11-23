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

    <?php if ($talent->user->id == \Yii::$app->user->id) { ?>
        <div class="container-fluid banner banner-cat" style="background-image: url('<?= ($imageBanner == null) ? Yii::getAlias('@web/images/banners/portfolio.jpg') : $imageBanner ?>');position: static;">
            <form id="profilefileupload" action="<?= yii\helpers\Url::to(['user/uploadprofile']); ?>" method="POST" enctype="multipart/form-data"> 
                <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                <a href="#" onclick="javascript:$('#profilefileupload input').click();" class="show-on-custom-link" aria-label="Upload profile">
                    <img src="<?= ($image == null) ? Yii::getAlias('@web/images/spotlight/profile-1.png') : $image ?>" alt="profile-image" class="portfolio-profile-img"/>    
                </a>
                <input type="file" name="PhotosForm[files]" id="filespro" style="display:none;">      
            </form>     
        </div>
    <?php } else { ?>
        <div class="container-fluid banner banner-cat" style="background-image: url('<?= ($imageBanner == null) ? Yii::getAlias('@web/images/banners/portfolio.jpg') : $imageBanner ?>');position: static;">
            <div>
                <img src="<?= ($image == null) ? Yii::getAlias('@web/images/spotlight/profile-1.png') : $image ?>" alt="profile-image" class="portfolio-profile-img"/>     
            </div>
        </div>
    <?php } ?>
    <div class="upload-banner-btn">
        <form id="bannerfileupload" action="<?= yii\helpers\Url::to(['user/uploadbanner']); ?>" method="POST" enctype="multipart/form-data"> 
            <?php if ($talent->user->id == \Yii::$app->user->id) { ?>
                <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                <a href="#" onclick="javascript:$('#bannerfileupload input').click();" class="show-on-custom-link" aria-label="Upload banner">
                    <div class="btn btn-primary">Upload Banner Image</div>   
                </a>
                <input type="file" name="PhotosForm[files]" id="filesban" style="display:none;">      
            <?php } ?>
        </form>
    </div>

    <!----- PORTFOLIO INTRO DIV START ----->
    <div class="portfolio-intro">
        <span class="port-intro-name"><?= Html::encode($talent->user->name) ?></span>
        <span class="country"><?= $talent->user->city != NULL ? $talent->user->city . '<br>' : '' ?></span>
        <span class="country"><?= $talent->user->state != NULL ? $talent->user->state . '<br>' : '' ?></span>
        <span class="country"><?= $talent->user->country != NULL ? $talent->user->country . '<br>' : '' ?></span>
        <span class="port-intro-talent"><?= $talent->talent->name ?></span>
        <span class="rating">Ratings: 
            <span class='portfolio-filled-star portfolio-star'>&#9733;</span>
            <span class='portfolio-filled-star portfolio-star'>&#9733;</span>
            <span class='portfolio-filled-star portfolio-star'>&#9733;</span>
            <span class='portfolio-filled-star portfolio-star'>&#9733;</span>
            <span class='portfolio-star'>&#9733;</span>
        </span><br><br>
        <?php if (!Yii::$app->user->isGuest) { ?>
            <a href="<?= Html::encode(Url::to(['/messages?user-id=' . $talent->user->id])) ?>"><button class="btn fan-btn">Be My Fan</button></a>
            <a href="<?= Html::encode(Url::to(['/messages?user-id=' . $talent->user->id])) ?>"><button class="btn contact-me-btn">Contact</button></a>
        <?php } ?>
        <!----- COLLAB DIV START ----->
        <div class="portfolio-collab-div_">
            <div style="padding: 5px;">
                <strong> Collab Points: &nbsp;</strong>
                <div class="collab-div center-align">
                    <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                  
                    <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                                          
                    <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                                          
                    <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                                          
                    <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                                          
                    <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                  
                    <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                                          
                    <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                                          
                    <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                                          
                    <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                                          
                </div>
            </div>
            <div style="padding: 5px;">
                <strong>Fans:</strong>
                6458
            </div>
            <strong> Fan Rating: &nbsp;</strong> 
            <div class="collab-div center-align">
                <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                  
                <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                                          
                <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                                          
                <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                                          
                <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                                          
                <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                  
                <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                                          
                <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                                          
                <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                                          
                <img class="portfolio-collab-star" src="<?= Yii::getAlias('@web/images/logo2.png') ?>" alt="Collab Point">                                          
            </div>  
        </div>
        <!----- COLLAB DIV END ----->
    </div>
    <!----- PORTFOLIO INTRO DIV END ----->

    <div class="container">
        <!----- FEATURE PRODUCT DIV START ----->
        <div class="row">
            <?= FeaturedPortfolioProducts::widget(['limit' => 4]) ?>
        </div>
        <!----- FEATURE PRODUCT DIV END ----->
        <hr class="portfolio-hr">
        <!----- VIDEO DIV START ----->
        <div class="portfolio-video">
            <div class="container">
                <div class="title-group text-center">
                    <h2 class="subtitle" style="color: aliceblue;margin: 20px;">VIDEO COLLECTION</h2>
                </div>
                <div id="portfolio-video" class="featured-products owl-carousel owl-theme">
                    <?php foreach ($videos as $video): ?>
                        <figure class="product-image-container">
                            <video class="gallery-img" controls>
                                <source src="<?= UserProfileImage::getGalleryVideoPath($video) ?>" type="video/<?= $video->image_extension ?>">
                            </video>
                        </figure>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!----- VIDEO DIV END ----->
        <hr class="portfolio-hr">
        <!----- PHOTOS DIV START ----->
        <div class="portfolio-video">
            <div class="container">
                <div class="title-group text-center">
                    <h2 class="subtitle" style="color: aliceblue;margin: 20px;">PHOTOS COLLECTION</h2>
                </div>
                <div id="portfolio-video" class="featured-products owl-carousel owl-theme">
                    <?php foreach ($photos as $photo): ?>
                        <figure class="product-image-container">
                            <img src="<?= UserProfileImage::getGalleryImagePath($photo) ?>" alt="gallery-image" class="hover-shadow cursor gallery-img"/>
                        </figure> 
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!----- PHOTOS DIV END ----->
        <hr class="portfolio-hr">
        <!----- PORTFOLIO PRODUCT DIV START ----->
        <div class="row">
            <?= PortfolioProducts::widget(['createdBy' => $id, 'talent_id' => $talent->id]) ?>
        </div>
        <!----- PORTFOLIO PRODUCT DIV END ----->
    </div>
</main>
<!-- End .main -->
<script>
    $('#filespro').change(function () {
        // submit the form 
        $('#profilefileupload').submit();
    });

    $('#filesban').change(function () {
        // submit the form 
        $('#bannerfileupload').submit();
    });
</script>