<?php

/* @var $this yii\web\View */
/* @var $user \shop\entities\User\User */
/* @var $product \shop\entities\Shop\Product\Product */

$link = Yii::$app->get('frontendUrlManager')->createAbsoluteUrl(['shop/catalog/product', 'id' => $product->id]);
?>
Hello <?= $user->name ?>,

Product from your wishlist is available right now:

<?= $link ?>