<?php
/* @var $this yii\web\View */
/* @var $cart \shop\cart\Cart */
/* @var $model \shop\forms\Shop\Order\OrderForm */

use shop\helpers\PriceHelper;
use shop\helpers\WeightHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Checkout';
$this->params['breadcrumbs'][] = ['label' => 'Catalog', 'url' => ['/shop/catalog/index']];
$this->params['breadcrumbs'][] = ['label' => 'Shopping Cart', 'url' => ['/shop/cart/index']];
$this->params['breadcrumbs'][] = $this->title;
//dd($model);
//dd($user_addresses);
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
        <ul class="checkout-progress-bar">
            <li class="active">
                <span>Shipping</span>
            </li>
            <li>
                <span>Review &amp; Payments</span>
            </li>
        </ul>
        <div class="row">
            <div class="col-lg-8">
                <ul class="checkout-steps">
                    <li>
                        <?php $form = ActiveForm::begin() ?>

                        <div class="panel panel-default">
                            <div class="panel-heading">Customer</div>
                            <div class="panel-body">
                                <?= $form->field($model->customer, 'phone')->textInput(['value' => \Yii::$app->user->identity->getUser()->phone]) ?>
                                <?= $form->field($model->customer, 'name')->textInput(['value' => \Yii::$app->user->identity->getUser()->name]) ?>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">Delivery</div>
                            <div class="panel-body">
                                <?= $form->field($model->delivery, 'method')->dropDownList($model->delivery->deliveryMethodsList(), ['prompt' => '--- Select ---']) ?>
                                <input type="hidden" name="DeliveryForm[index]" value="1" />
                                <?php // echo $form->field($model->delivery, 'address')->textarea(['rows' => 3]) ?>
                                <div class="form-group field-deliveryform-address required">
                                    <label class="control-label" for="deliveryform-address">Address</label>
                                    <?php ?>
                                    <select id="deliveryform-address" class="form-control" name="DeliveryForm[address]" required="" aria-required="true">
                                        <option value="">--- Select ---</option>
                                        <?php
                                        if ($user_addresses) {
                                            foreach ($user_addresses as $user_address) {
                                                ?>
                                                <option <?= $user_address->default == 1 ? 'selected' : '' ?> value="<?= $user_address->id ?>"><?= $user_address->address ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php if (!$user_addresses) { ?>
                                    <br>
                                        <div class="login-signup-btn-padding">
                                            <a href="<?= Html::encode(Url::to(['/addaddress'])) ?>" class="paction product-promote-btn login-signup-btn" title="SignUp">Add Address</a>
                                        </div>
                                    <?php } ?>

                                    <p class="help-block help-block-error"></p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">Note</div>
                            <div class="panel-body">
                                <?= $form->field($model, 'note')->textarea(['rows' => 3])->label(false) ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= Html::submitButton('Checkout', ['class' => 'btn btn-primary btn-lg btn-block']) ?>
                        </div>

                        <?php ActiveForm::end() ?>

                    </li>
                </ul>
            </div><!-- End .col-lg-8 -->

            <div class="col-lg-4">
                <div class="order-summary">
                    <h3>Summary</h3>

                    <h4>
                        <a data-toggle="collapse" href="#order-cart-section" class="collapsed" role="button" aria-expanded="false" aria-controls="order-cart-section">products in Cart</a>
                    </h4>

                    <div class="collapse" id="order-cart-section">
                        <table class="table table-mini-cart">
                            <tbody>
                                <?php foreach ($cart->getItems() as $item): ?>
                                    <?php
                                    $product = $item->getProduct();
                                    $modification = $item->getModification();
                                    $url = Url::to(['/shop/catalog/product', 'id' => $product->id]);
                                    ?>
                                    <tr>
                                        <td class="product-col">
                                            <?php if ($product->mainPhoto): ?>
                                                <a href="<?= $url ?>" class="product-image-container">
                                                    <img src="<?= $product->mainPhoto->getThumbFileUrl('file', 'cart_list') ?>" alt="product" />
                                                </a>
                                            <?php endif; ?>
                                            <div>
                                                <figure class="product-image-container"></figure>
                                                <h2 class="product-title">
                                                    <a href="<?= $url ?>"><?= Html::encode($product->name) ?></a>
                                                </h2>

                                                <span class="product-qty">Qty:  <?= $item->getQuantity() ?></span>
                                            </div>
                                        </td>
                                        <td class="price-col"> <?= $item->getCost() ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php $cost = $cart->getCost() ?>
                                <tr>
                                    <td class="product-col text-right">
                                        <h2 class="product-title">
                                            Sub-Total:
                                        </h2>
                                    </td>
                                    <td class=""><strong> <?= PriceHelper::format($cost->getOrigin()) ?></strong></td>
                                </tr>
                            </tbody>    
                        </table>
                    </div><!-- End #order-cart-section -->
                </div><!-- End .order-summary -->
            </div><!-- End .col-lg-4 -->
        </div><!-- End .row -->

        <div class="mb-6"></div><!-- margin -->
</main><!-- End .main -->