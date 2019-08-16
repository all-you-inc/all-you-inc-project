<?php

/* @var $cart \shop\cart\Cart */

use shop\helpers\PriceHelper;
use yii\helpers\Html;
use yii\helpers\Url;
?>
    <div class="dropdown cart-dropdown">

                    <a href="#" class="dropdown-toggle menu-gray" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                        <span class="cart-count"><?= $cart->getAmount() ?></span>
                    </a>
                    <div class="dropdown-menu" >
                        <div class="dropdownmenu-wrapper">
                            <div class="dropdown-cart-products">
                                <?php foreach ($cart->getItems() as $item): ?>
                                    <?php
                                    $product = $item->getProduct();
                                    $modification = $item->getModification();
                                    $url = Url::to(['/shop/catalog/product', 'id' => $product->id]);

                                    ?>
                                    <div class="product">
                                        <div class="product-details">
                                            <h4 class="product-title">
                                                 <a href="<?= $url ?>"><?= Html::encode($product->name) ?></a>
                                            </h4>

                                            <span class="cart-product-info">
                                                <span class="cart-product-qty"><?= $item->getQuantity() ?></span>
                                                x <?= PriceHelper::format($item->getPrice()) ?>
                                            </span>
                                        </div><!-- End .product-details -->

                                        <figure class="product-image-container">
                                            <?php if ($product->mainPhoto): ?>
                                                <a href="<?= $url ?>" class="product-image">
                                                    <img src="<?= $product->mainPhoto->getThumbFileUrl('file', 'cart_list') ?>" alt="product" />
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?= Url::to(['/shop/cart/remove', 'id' => $item->getId()]) ?>" class="btn-remove" title="Remove Product"><i class="icon-cancel"></i></a>
                                        </figure>
                                    </div><!-- End .product -->
                                <?php endforeach; ?>
                            </div><!-- End .cart-product -->

                            <div class="dropdown-cart-total">
                                <span>Total</span>

                                <span class="cart-total-price"><?= PriceHelper::format($cart->getCost()->getOrigin()) ?></span>
                            </div><!-- End .dropdown-cart-total -->

                            <div class="dropdown-cart-action">
                                <a href="<?= Url::to('/shop/cart/index') ?>" class="btn">View Cart</a>
                                <a href="<?= Url::to('/shop/checkout/index') ?>" class="btn">Checkout</a>
                            </div><!-- End .dropdown-cart-total -->
                        </div><!-- End .dropdownmenu-wrapper -->
                    </div><!-- End .dropdown-menu -->
                </div><!-- End .dropdown -->
