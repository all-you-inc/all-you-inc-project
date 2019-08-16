<?php
/* @var $this yii\web\View */
/* @var $cart \shop\cart\Cart */

use shop\helpers\PriceHelper;
use shop\helpers\WeightHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Shopping Cart';
$this->params['breadcrumbs'][] = ['label' => 'Catalog', 'url' => ['/shop/catalog/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container-fluid">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= \yii\helpers\Url::home() ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
            </ol>
        </div><!-- End .container-fluid -->
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="cart-table-container">
                    <table class="table table-cart">
                        <thead>
                            <tr>
                                <th class="product-col">Product</th>
                                <th class="price-col">Price</th>
                                <th class="qty-col">Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart->getItems() as $item): ?>
                                <?php
                                $product = $item->getProduct();
                                $modification = $item->getModification();
                                $url = Url::to(['/shop/catalog/product', 'id' => $product->id]);
                                ?>
                                <tr class="product-row">
                                    <td class="product-col">
                                        <figure class="product-image-container">
                                            <?php if ($product->mainPhoto): ?>
                                                <a href="<?= $url ?>" class="product-image">
                                                    <img src="<?= $product->mainPhoto->getThumbFileUrl('file', 'cart_list') ?>" alt="product" />
                                                </a>
                                            <?php endif; ?>
                                        </figure>
                                        <h2 class="product-title">
                                            <a href="<?= $url ?>"><?= Html::encode($product->name) ?></a>
                                        </h2>
                                    </td>
                                    <td><?= PriceHelper::format($item->getPrice()) ?></td>

                                    <td>
                                        <?= Html::beginForm(['quantity', 'id' => $item->getId()],'POST',$options = ['class' => 'cart-form']); ?>
                                        <input type="text" name="quantity" value="<?= $item->getQuantity() ?>" size="1" class="vertical-quantity form-control" />
                                    </td>
                                    <td><?= PriceHelper::format($item->getCost()) ?></td>
                                </tr>
                                <tr class="product-action-row">
                                    <td colspan="4" class="clearfix">
                                        <div class="float-right">
                                            <button type="submit" title="Edit product" class="btn-edit"><i class="icon-pencil">update</i></button>
                                            <a href="<?= Url::to(['remove', 'id' => $item->getId()]) ?>" title="Remove product" class="btn-remove"></a>
                                        </div><!-- End .float-right -->
                                    </td>
                                </tr>
                                <?= Html::endForm() ?>
                            <?php endforeach; ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="4" class="clearfix">
                                    <div class="float-left">
                                        <a href="<?= Url::to('/shop/catalog/index') ?>" class="btn btn-outline-secondary">Continue Shopping</a>
                                    </div> <!--End .float-left -->
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div><!-- End .cart-table-container -->
            </div><!-- End .col-lg-8 -->

            <div class="col-lg-4">
                <div class="cart-summary">
                    <h3>Summary</h3>
                    <table class="table table-totals">
                        <tbody>
                            <tr>
                                <td>Subtotal</td>
                                <td><?= PriceHelper::format($cart->getCost()->getOrigin()) ?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Order Total</td>
                                <td><?= PriceHelper::format($cart->getCost()->getOrigin()) ?></td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="checkout-methods">
                        <a href="<?= Url::to('/shop/checkout/index') ?>" class="btn btn-block btn-sm btn-primary">Go to Checkout</a>
                    </div><!-- End .checkout-methods -->
                </div><!-- End .cart-summary -->
            </div><!-- End .col-lg-4 -->
        </div><!-- End .row -->
    </div><!-- End .container -->

    <div class="mb-6"></div><!-- margin -->
</main><!-- End .main -->

