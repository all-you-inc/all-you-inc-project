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

        <div class="toolbox-item toolbox-show">
            <!-- Search Area -->
        </div><!-- End .toolbox-item -->
        <ul class="pagination">
            <?=
                LinkPager::widget([
                    'pagination' => $pages,
                    'activePageCssClass' => 'page-item active',
                    'maxButtonCount' => 8,
                    'linkOptions' => ['class' => 'page-item page-link'],
                    'disabledPageCssClass' => 'disabled',
                ]);
            ?>
        </ul>
    </nav>

    <div class="row row-sm">
        <?php if($dataProvider != null){ ?>
            <?php foreach ($dataProvider as $product):
                ?>
                <?=
                $this->render('_product', [
                    'product' => $product
                ])
                ?>
            <?php endforeach; ?>
        <?php }else {  ?>
            <h1>NO RESULT FOUND</h1>
        <?php } ?>
    </div><!-- End .row -->

    <nav class="toolbox toolbox-pagination">
        <div class="toolbox-item toolbox-show">
        </div><!-- End .toolbox-item -->
        <ul class="pagination">
            <?=
                LinkPager::widget([
                    'pagination' => $pages,
                    'activePageCssClass' => 'page-item active',
                    'maxButtonCount' => 8,
                    'linkOptions' => ['class' => 'page-item page-link'],
                    'disabledPageCssClass' => 'disabled',
                ]);
            ?>
        </ul>
    </nav>
</div>