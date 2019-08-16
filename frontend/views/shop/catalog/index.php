<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $category shop\entities\Shop\Category */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\widgets\Shop\FeaturedProductsWidget;

$this->title = 'Catalog';
$this->params['breadcrumbs'][] = $this->title;
?>
<main class="main">
    <div class="banner banner-cat" style="background-image: url('assets/images/banners/banner-top.jpg');">
        <div class="banner-content container">
            <h3 class="banner-subtitle">check out over <strong>200+</strong></h3>
            <h1 class="banner-title">INCREDIBLE deals</h1>

            <a href="#" class="btn btn-primary" role="button">Shop Now</a>
        </div><!-- End .banner-content -->
    </div><!-- End .banner -->

    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container-fluid ">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= \yii\helpers\Url::home() ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
            </ol>
        </div><!-- End .container-fluid  -->
    </nav>

    <div class="container-fluid">
        <div class="row">
<?=
$this->render('_list', [
    'dataProvider' => $dataProvider
])
?>
<?=
$this->render('_sidebar', [
    'categories' => $categories,
    'brands' => $brands,
])
?>

        </div>
    </div><!-- End .container-fluid -->

</main><!-- End .main -->

