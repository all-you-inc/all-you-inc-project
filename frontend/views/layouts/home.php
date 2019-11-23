<?php
/* @var $this \yii\web\View */
/* @var $content string */
use frontend\assets\OwlCarouselAsset;
use frontend\widgets\Shop\FeaturedProductsWidget;
OwlCarouselAsset::register($this);
?>
<?php $this->beginContent('@frontend/views/layouts/main.php') ?>
<?= $this->render('shared/home/main_slider') ?>
<?php // $this->render('shared/home/main_banner') ?>
<?= $this->render('shared/home/spotlight') ?>
<?= $this->render('shared/home/home_body') ?>
<?= FeaturedProductsWidget::widget(['limit' => 5])?>
<?php $this->endContent() ?>