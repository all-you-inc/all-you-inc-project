<?php

/** @var $products shop\entities\Shop\Product\Product[] */
use shop\helpers\PriceHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
?>

<div class="container" style="margin: 4% 0 0 0;">
    <div class="title-group text-center">
        <h2 class="subtitle">FEATURED PRODUCTS</h2>
    </div>
    <div class="featured-products owl-carousel owl-theme">
        <?php foreach ($products as $product): ?>
            <?php $url = Url::to(['/shop/catalog/product', 'id' => $product->id]); ?>
           
                <div class="product">
                    <figure class="product-image-container">
                        <?php if ($product->mainPhoto): ?>
                            <a href="<?= Html::encode($url) ?>" class="product-image">
                                <img src="<?= Html::encode($product->mainPhoto->getThumbFileUrl('file', 'catalog_list')) ?>" alt="product">
                            </a>
                        <?php endif; ?>
                        <!--604800 for 1 week-->
                        <?= (time() - $product->created_at) < 604800 ? '<span class="product-label label-new">New</span>' : '' ?>
                        <!--86400 for 1 day-->
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
            </a>
        <?php endforeach; ?>
    </div><!-- End .featured-products -->
</div><!-- End .container-fluid -->
