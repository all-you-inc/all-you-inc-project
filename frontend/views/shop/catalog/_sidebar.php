<?php
/* @var $category shop\entities\Shop\Category */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\widgets\Shop\FeaturedProductsWidget;
?>
<aside class="sidebar-shop col-lg-3 col-xxl-2 order-lg-first">
    <div class="sidebar-wrapper">
        <div class="widget">
            <h3 class="widget-title">
                <a data-toggle="collapse" href="#widget-body-1" role="button" aria-expanded="true" aria-controls="widget-body-1">Categories</a>
            </h3>

            <div class="collapse show" id="widget-body-1">
                <div class="widget-body">
                    <ul class="cat-list">
                        <?php foreach ($categories as $child) { ?>
                            <li>  <a href="<?= Html::encode(Url::to(['/shop/catalog/category', 'id' => $child->id])) ?>"><?= Html::encode($child->name) ?></a></li>
                        <?php } ?>

                    </ul>
                </div><!-- End .widget-body -->
            </div><!-- End .collapse -->
        </div>
        <!-- End .widget -->

        <div class="widget">
            <h3 class="widget-title">
                <a data-toggle="collapse" href="#widget-body-4" role="button" aria-expanded="true" aria-controls="widget-body-4">Brands</a>
            </h3>

            <div class="collapse show" id="widget-body-4">
                <div class="widget-body">
                    <ul class="cat-list">
                        <?php foreach ($brands as $child) {
                            ?>
                            <li>  <a href="<?= Html::encode(Url::to(['/shop/catalog/brand', 'id' => $child->id])) ?>"><?= Html::encode($child->name) ?></a></li>
                        <?php } ?>
                    </ul>
                </div><!-- End .widget-body -->
            </div><!-- End .collapse -->
        </div>
        <!-- End .widget -->
    </div>
    <!-- End .sidebar-wrapper -->
</aside>
<!-- End .col-lg-3 -->