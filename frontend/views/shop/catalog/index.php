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
    'dataProvider' => $dataProvider,
    'pages' => $pages,
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

