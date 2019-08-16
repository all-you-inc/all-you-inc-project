<?php
/* @var $this \yii\web\View */
/* @var $content string */
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Category;
use shop\helpers\PriceHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

?>
<?php
$category = Category::find()->where(['is_dashboard' => 1])->limit(2)->orderBy('id ASC')->all();
if ($category) {
    $products = Product::find()->where(['category_id' => $category[0]->id, 'status' => 1])->limit(3)->orderBy('id DESC')->all();
    if ($products) {
        ?>
        <div class="half-section">
            <div class="row no-gutters">
                <div class="col-md-6 order-md-last half-img" style="background-image: url('<?= Yii::getAlias('@web/images/bg-1.jpg') ?>');">
                    <h2 class="half-title"><?= $category[0]->name ?></h2>
                </div><!-- End .col-md-6 -->

                <div class="col-md-6">
                    <div class="half-content">
                        <div class="title-group">
                            <h2 class="subtitle"><?= $category[0]->name ?></h2>
                            <p><?= $category[0]->description ?></p>
                        </div>

                        <div class="products-slider owl-carousel owl-theme owl-nav-top">
                            <?php
                            foreach ($products as $product) {
                                $url = Url::to(['/shop/catalog/product', 'id' => $product->id]);
                                ?>
                                <div class="product">
                                    <figure class="product-image-container">
                                        <a href="<?= Html::encode($url) ?>" class="product-image">
                                            <img src="<?= Html::encode($product->mainPhoto->getThumbFileUrl('file', 'catalog_list')) ?>" alt="product">
                                        </a>
                                        <?= (time() - $product->created_at) < 604800 ? '<span class="product-label label-new">New</span>' : '' ?>
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
                            <?php } ?>
                        </div><!-- End .products-slider -->
                    </div><!-- End .half-content -->
                </div><!-- End .col-md-6 -->
            </div><!-- End .no-gutters -->
        </div><!-- End .half-section -->
        <?php
    }
    $products = Product::find()->where(['category_id' => $category[1]->id, 'status' => 1])->limit(3)->orderBy('id DESC')->all();
    if ($products) {
        ?>
        <div class="half-section">
            <div class="row no-gutters">
                <div class="col-md-6 half-img" style="background-image: url('<?= Yii::getAlias('@web/images/bg-2.jpg') ?>');">
                    <h2 class="half-title"><?= $category[1]->name ?></h2>
                </div><!-- End .col-md-6 -->

                <div class="col-md-6">
                    <div class="half-content">
                        <div class="title-group">
                            <h2 class="subtitle"><?= $category[1]->name ?></h2>
                            <p><?= $category[1]->description ?></p>
                        </div>

                        <div class="products-slider owl-carousel owl-theme owl-nav-top">
                            <?php
                            foreach ($products as $product) {
                                $url = Url::to(['/shop/catalog/product', 'id' => $product->id]);
                                ?>
                                <div class="product">
                                    <figure class="product-image-container">
                                        <a href="<?= Html::encode($url) ?>" class="product-image">
                                            <img src="<?= Html::encode($product->mainPhoto->getThumbFileUrl('file', 'catalog_list')) ?>" alt="product">
                                        </a>
                                        <?= (time() - $product->created_at) < 604800 ? '<span class="product-label label-new">New</span>' : '' ?>
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
                            <?php } ?>
                        </div><!-- End .products-slider -->
                    </div><!-- End .half-content -->
                </div><!-- End .col-md-6 -->
            </div><!-- End .no-gutters -->
        </div><!-- End .half-section -->
    <?php }
} ?>