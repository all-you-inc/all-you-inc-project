<?php
/* @var $this yii\web\View */
/* @var $product shop\entities\Shop\Product\Product */
/* @var $cartForm shop\forms\Shop\AddToCartForm */
/* @var $reviewForm shop\forms\Shop\ReviewForm */

use frontend\assets\MagnificPopupAsset;
use shop\helpers\PriceHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\widgets\Shop\FeaturedProductsWidget;

$this->title = $product->name;

$this->registerMetaTag(['name' => 'description', 'content' => $product->meta->description]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $product->meta->keywords]);

$this->params['breadcrumbs'][] = ['label' => 'Catalog', 'url' => ['index']];
foreach ($product->category->parents as $parent) {
    if (!$parent->isRoot()) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category', 'id' => $parent->id]];
    }
}
$this->params['breadcrumbs'][] = ['label' => $product->category->name, 'url' => ['category', 'id' => $product->category->id]];
$this->params['breadcrumbs'][] = $product->name;

$this->params['active_category'] = $product->category;

MagnificPopupAsset::register($this);
?>
<nav aria-label="breadcrumb" class="breadcrumb-nav">
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= \yii\helpers\Url::home() ?>">
                    <!--<i class="icon-home"></i>-->
                    Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
        </ol>
    </div><!-- End .container-fluid -->
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-9">
            <div class="product-single-container product-single-default">
                <div class="row">
                    <div class="col-lg-7 col-md-6 product-single-gallery">
                        <div class="product-slider-container product-item">
                            <div class="product-single-carousel owl-carousel owl-theme">

                                <?php foreach ($product->photos as $i => $photo): ?>
                                    <div class="product-item">
                                        <img class="product-single-image"  alt="<?= Html::encode($product->name) ?>"  src="<?= $photo->getThumbFileUrl('file', 'catalog_product_main') ?>" data-zoom-image="<?= $photo->getThumbFileUrl('file', 'catalog_product_main') ?>"/>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <!-- End .product-single-carousel -->
                            <span class="prod-full-screen">
                                <i class="icon-plus"></i>
                            </span>
                        </div>
                        <div class="prod-thumbnail row owl-dots" id='carousel-custom-dots'>
                            <?php foreach ($product->photos as $i => $photo): ?>
                                <div class="col-3 owl-dot">
                                    <img alt="<?= Html::encode($product->name) ?>"  src="<?= $photo->getThumbFileUrl('file', 'catalog_product_main') ?>" data-zoom-image="<?= $photo->getThumbFileUrl('file', 'catalog_product_main') ?>"/>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div><!-- End .col-lg-7 -->

                    <div class="col-lg-5 col-md-6">
                        <div class="product-single-details">
                            <h1 class="product-title"><?= Html::encode($product->name) ?></h1>

                            <div class="price-box">
                                <span class="old-price"><?= PriceHelper::format($product->price_old) ?></span>
                                <span class="product-price"><?= PriceHelper::format($product->price_new) ?></span>
                            </div><!-- End .price-box -->

                            <div class="product-desc">
                                <?=
                                Yii::$app->formatter->asHtml($product->description, [
                                    'Attr.AllowedRel' => array('nofollow'),
                                    'HTML.SafeObject' => true,
                                    'Output.FlashCompat' => true,
                                    'HTML.SafeIframe' => true,
                                    'URI.SafeIframeRegexp' => '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
                                ])
                                ?>
                            </div>
                            <div class="product-action product-all-icons">

                                <a href="<?= Url::to(['/shop/cart/add', 'id' => $product->id]) ?>" class="paction add-cart" title="Add to Cart">
                                    <span>Add to Cart</span>
                                </a>
                                <?php
                                if (!\Yii::$app->user->isGuest && \Yii::$app->user->identity->getUser()->isPromoter()) {
                                    $shareUrl = Url::base('http') . Url::to(['/catalog/'.$product->id.'?referral='. \Yii::$app->user->identity->getUser()->referral_code]);
                                    ?>
                                    <a onclick="prompt('To Promote Press Ctrl + C, then Enter to copy to clipboard', '<?php echo $shareUrl ?>')"
                                       class="paction product-promote-btn" title="Promote">
                                        Promote
                                    </a>
                                        <!--<button class="float-right paction btn-primary" onclick="prompt('To Promote Press Ctrl + C, then Enter to copy to clipboard', '<?php echo $shareUrl ?>')">Promote</button>-->
                                <?php } ?>    
                            </div><!-- End .product-action -->
                        </div><!-- End .product-single-details -->
                    </div><!-- End .col-lg-5 -->
                </div><!-- End .row -->
            </div><!-- End .product-single-container -->

            <div class="product-single-tabs">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="product-tab-desc" data-toggle="tab" href="#product-desc-content" role="tab" aria-controls="product-desc-content" aria-selected="true">Description</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="product-tab-reviews" data-toggle="tab" href="#product-reviews-content" role="tab" aria-controls="product-reviews-content" aria-selected="false">Reviews</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="product-desc-content" role="tabpanel" aria-labelledby="product-tab-desc">
                        <div class="product-desc-content">
                            <?=
                            Yii::$app->formatter->asHtml($product->description, [
                                'Attr.AllowedRel' => array('nofollow'),
                                'HTML.SafeObject' => true,
                                'Output.FlashCompat' => true,
                                'HTML.SafeIframe' => true,
                                'URI.SafeIframeRegexp' => '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
                            ])
                            ?>
                        </div><!-- End .product-desc-content -->
                    </div><!-- End .tab-pane -->

                    <div class="tab-pane fade" id="product-tags-content" role="tabpanel" aria-labelledby="product-tab-tags">
                        <div class="product-tags-content">
                            <form action="#">
                                <h4>Add Your Tags:</h4>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" required>
                                    <input type="submit" class="btn btn-primary" value="Add Tags">
                                </div><!-- End .form-group -->
                            </form>
                            <p class="note">Use spaces to separate tags. Use single quotes (') for phrases.</p>
                        </div><!-- End .product-tags-content -->
                    </div><!-- End .tab-pane -->

                    <div class="tab-pane fade" id="product-reviews-content" role="tabpanel" aria-labelledby="product-tab-reviews">
                        <div class="product-reviews-content">
                            <div class="collateral-box">
                                <ul>
                                    <li>Be the first to review this product</li>
                                </ul>
                            </div><!-- End .collateral-box -->

                            <div class="add-product-review">
                                <h3 class="text-uppercase heading-text-color font-weight-semibold">WRITE YOUR OWN REVIEW</h3>
                                <p>How do you rate this product? *</p>

                                <form action="#">
                                    <table class="ratings-table">
                                        <thead>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>1 star</th>
                                                <th>2 stars</th>
                                                <th>3 stars</th>
                                                <th>4 stars</th>
                                                <th>5 stars</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Quality</td>
                                                <td>
                                                    <input type="radio" name="ratings[1]" id="Quality_1" value="1" class="radio">
                                                </td>
                                                <td>
                                                    <input type="radio" name="ratings[1]" id="Quality_2" value="2" class="radio">
                                                </td>
                                                <td>
                                                    <input type="radio" name="ratings[1]" id="Quality_3" value="3" class="radio">
                                                </td>
                                                <td>
                                                    <input type="radio" name="ratings[1]" id="Quality_4" value="4" class="radio">
                                                </td>
                                                <td>
                                                    <input type="radio" name="ratings[1]" id="Quality_5" value="5" class="radio">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Value</td>
                                                <td>
                                                    <input type="radio" name="value[1]" id="Value_1" value="1" class="radio">
                                                </td>
                                                <td>
                                                    <input type="radio" name="value[1]" id="Value_2" value="2" class="radio">
                                                </td>
                                                <td>
                                                    <input type="radio" name="value[1]" id="Value_3" value="3" class="radio">
                                                </td>
                                                <td>
                                                    <input type="radio" name="value[1]" id="Value_4" value="4" class="radio">
                                                </td>
                                                <td>
                                                    <input type="radio" name="value[1]" id="Value_5" value="5" class="radio">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Price</td>
                                                <td>
                                                    <input type="radio" name="price[1]" id="Price_1" value="1" class="radio">
                                                </td>
                                                <td>
                                                    <input type="radio" name="price[1]" id="Price_2" value="2" class="radio">
                                                </td>
                                                <td>
                                                    <input type="radio" name="price[1]" id="Price_3" value="3" class="radio">
                                                </td>
                                                <td>
                                                    <input type="radio" name="price[1]" id="Price_4" value="4" class="radio">
                                                </td>
                                                <td>
                                                    <input type="radio" name="price[1]" id="Price_5" value="5" class="radio">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="form-group">
                                        <label>Nickname <span class="required">*</span></label>
                                        <input type="text" class="form-control form-control-sm" required>
                                    </div><!-- End .form-group -->
                                    <div class="form-group">
                                        <label>Summary of Your Review <span class="required">*</span></label>
                                        <input type="text" class="form-control form-control-sm" required>
                                    </div><!-- End .form-group -->
                                    <div class="form-group mb-2">
                                        <label>Review <span class="required">*</span></label>
                                        <textarea cols="5" rows="6" class="form-control form-control-sm"></textarea>
                                    </div><!-- End .form-group -->

                                    <input type="submit" class="btn btn-primary" value="Submit Review">
                                </form>
                            </div><!-- End .add-product-review -->
                        </div><!-- End .product-reviews-content -->
                    </div><!-- End .tab-pane -->
                </div><!-- End .tab-content -->
            </div><!-- End .product-single-tabs -->
        </div><!-- End .col-lg-9 -->

        <div class="sidebar-overlay"></div>
        <div class="sidebar-toggle"><i class="icon-sliders"></i></div>
        <aside class="sidebar-product col-lg-3 padding-left-lg mobile-sidebar">
            <div class="sidebar-wrapper">
                <div class="widget widget-brand">
                    <a href="#">
                        <img src="<?= Yii::getAlias('@web/images/product-brand.png') ?>" alt="brand name">
                    </a>
                </div><!-- End .widget -->

                <div class="widget widget-info">
                    <ul>
                        <li>
                            <i class="icon-shipping"></i>
                            <h4>FREE<br>SHIPPING</h4>
                        </li>
                        <li>
                            <i class="icon-us-dollar"></i>
                            <h4>100% MONEY<br>BACK GUARANTEE</h4>
                        </li>
                        <li>
                            <i class="icon-online-support"></i>
                            <h4>ONLINE<br>SUPPORT 24/7</h4>
                        </li>
                    </ul>
                </div><!-- End .widget -->

                <div class="widget widget-banner">
                    <div class="banner banner-image">
                        <a href="#">
                            <img src="" alt="brand name">
                            <img src="<?= Yii::getAlias('@web/images/banners/banner-sidebar.jpg') ?>" alt="Banner Desc">
                        </a>
                    </div><!-- End .banner -->
                </div><!-- End .widget -->
            </div>
        </aside><!-- End .col-md-3 -->
    </div><!-- End .row -->
</div><!-- End .container-fluid -->
<?=
FeaturedProductsWidget::widget([
    'limit' => 6,
])
?>

