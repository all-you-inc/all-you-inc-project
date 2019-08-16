<?php
/* @var $this yii\web\View */
/* @var $product shop\entities\Shop\Product\Product */

use shop\helpers\PriceHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

$url = Url::to(['product', 'id' => $product->id]);
?>
<div class="col-6 col-md-4">
    <div class="product">
        <figure class="product-image-container">
            <?php if ($product->mainPhoto): ?>
                <a href="<?= Html::encode($url) ?>" class="product-image">
                    <img src="<?= Html::encode($product->mainPhoto->getThumbFileUrl('file', 'catalog_list')) ?>" alt="product">
                </a>
            <?php endif; ?>
        </figure>
        <div class="product-details">
            <div class="ratings-container">
            </div><!-- End .product-container -->
            <h2 class="product-title">
                <a href="<?= Html::encode($url) ?>"><?= $product->name ?></a>
            </h2>
            <div class="price-box">
                <span class="old-price"><?= PriceHelper::format($product->price_old) ?></span>
                <span class="product-price"><?= PriceHelper::format($product->price_new) ?></span>
            </div><!-- End .price-box -->

            <div class="product-action">
                <a href="<?= Url::to(['/shop/cart/add', 'id' => $product->id]) ?>" class="paction add-cart" title="Add to Cart">
                    <span>Add to Cart</span>
                </a>
            </div><!-- End .product-action -->
        </div><!-- End .product-details -->
    </div><!-- End .product -->
</div><!-- End .col-md-4 -->
