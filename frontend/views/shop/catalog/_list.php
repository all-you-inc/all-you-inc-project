<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use frontend\widgets\Shop\FeaturedProductsWidget;
?>
<div class="col-lg-9 col-xxl-10">
    <nav class="toolbox">
        <div class="toolbox-left">
            <div class="toolbox-item toolbox-sort">
                <div class="select-custom">
                    <select name="orderby" class="form-control">
                        <option value="menu_order" selected="selected">Default sorting</option>
                        <option value="popularity">Sort by popularity</option>
                        <option value="rating">Sort by average rating</option>
                        <option value="date">Sort by newness</option>
                        <option value="price">Sort by price: low to high</option>
                        <option value="price-desc">Sort by price: high to low</option>
                    </select>
                </div><!-- End .select-custom -->

                <a href="#" class="sorter-btn" title="Set Ascending Direction"><span class="sr-only">Set Ascending Direction</span></a>
            </div><!-- End .toolbox-item -->
        </div><!-- End .toolbox-left -->

        <div class="toolbox-item toolbox-show">
        </div><!-- End .toolbox-item -->
    </nav>

    <div class="row row-sm">
        <?php foreach ($dataProvider as $product):
            ?>
            <?=
            $this->render('_product', [
                'product' => $product
            ])
            ?>
        <?php endforeach; ?>

    </div><!-- End .row -->

    <nav class="toolbox toolbox-pagination">
        <div class="toolbox-item toolbox-show">
        </div><!-- End .toolbox-item -->
    </nav>
</div>