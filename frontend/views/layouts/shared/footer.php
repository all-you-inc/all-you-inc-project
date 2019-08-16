<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<footer class="footer">
    <div class="info-boxes-container">
        <div class="container">
            <div class="info-box">
                <i class="icon-shipping"></i>

                <div class="info-box-content">
                    <h4>FREE SHIPPING & RETURN</h4>
                    <p>Free shipping on all orders over $99.</p>
                </div><!-- End .info-box-content -->
            </div><!-- End .info-box -->

            <div class="info-box">
                <i class="icon-us-dollar"></i>

                <div class="info-box-content">
                    <h4>MONEY BACK GUARANTEE</h4>
                    <p>100% money back guarantee</p>
                </div><!-- End .info-box-content -->
            </div><!-- End .info-box -->

            <div class="info-box">
                <i class="icon-support"></i>

                <div class="info-box-content">
                    <h4>ONLINE SUPPORT 24/7</h4>
                    <p>Lorem ipsum dolor sit amet.</p>
                </div><!-- End .info-box-content -->
            </div><!-- End .info-box -->
        </div><!-- End .container -->
    </div><!-- End .info-boxes-container -->

    <div class="container-fluid">
        <div class="footer-top">
            <div class="row">
                <div class="col-lg-10">
                    <div class="widget widget-newsletter">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="widget-title">Subscribe newsletter</h4>
                                <p>Get all the latest information on Events,Sales and Offers. Sign up for newsletter today</p>
                            </div><!-- End .col-lg-6 -->

                            <div class="col-lg-6">
                                <form action="#">
                                    <input type="email" class="form-control" placeholder="Email address" required>

                                    <input type="submit" class="btn" value="Subscribe">
                                </form>
                            </div><!-- End .col-lg-6 -->
                        </div><!-- End .row -->
                    </div><!-- End .widget -->
                </div><!-- End .col-md-9 -->

                <div class="col-lg-2 widget-social">
                    <div class="social-icons">
                        <a href="#" class="social-icon" target="_blank"><i class="icon-facebook"></i></a>
                        <a href="#" class="social-icon" target="_blank"><i class="icon-twitter"></i></a>
                        <a href="#" class="social-icon" target="_blank"><i class="icon-linkedin"></i></a>
                    </div><!-- End .social-icons -->
                </div><!-- End .col-md-3 -->
            </div><!-- End .row -->
        </div><!-- End .footer-top -->
    </div><!-- End .container-fluid -->

    <div class="footer-middle">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3">
                    <div class="widget">
                        <h4 class="widget-title">Contact Info</h4>
                        <ul class="contact-info">
                            <li>
                                <span class="contact-info-label">Address:</span>123 Street Name, City, Country
                            </li>
                            <li>
                                <span class="contact-info-label">Phone:</span>Toll Free <a href="tel:">(123) 456-7890</a>
                            </li>
                            <li>
                                <span class="contact-info-label">Email:</span> <a href="mailto:mail@example.com">mail@example.com</a>
                            </li>
                        </ul>
                    </div><!-- End .widget -->
                </div><!-- End .col-lg-3 -->

                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="widget">
                                <h4 class="widget-title">My Account</h4>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <ul class="links">
                                            <li><a href="#">About Us</a></li>
                                            <li><a href="#">Contact Us</a></li>
                                            <li><a href="#">My Account</a></li>
                                        </ul>
                                    </div><!-- End .col-sm-6 -->
                                    <div class="col-sm-6">
                                        <ul class="links">
                                            <li><a href="<?= Html::encode(Url::to(['/cabinet/order/index'])) ?>">Orders History</a></li>
                                            <li><a href="#">Advanced Search</a></li>
                                            <?php if (Yii::$app->user->isGuest): ?>
                                                <li><a href="<?= Html::encode(Url::to(['/auth/auth/login'])) ?>">Login</a></li>
                                            <?php else: ?>
                                                <li><a href="<?= Html::encode(Url::to(['/auth/auth/logout'])) ?>" data-method="post">Logout</a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div><!-- End .col-sm-6 -->
                                </div><!-- End .row -->
                            </div><!-- End .widget -->
                        </div><!-- End .col-md-4 -->

                        <div class="col-lg-5">
                        </div><!-- End .col-md-5 -->

                        <div class="col-lg-3">
                            <div class="widget">
                                <h4 class="widget-title">Working Days/Hours</h4>
                                <ul class="contact-info">
                                    <li>
                                        Mon - Sun / 9:00AM - 8:00PM
                                    </li>
                                </ul>
                            </div><!-- End .widget -->
                        </div><!-- End .col-md-33 -->
                    </div><!-- End .row -->

                    <div class="footer-bottom">
                        <p class="footer-copyright">All You Media &copy;  <?= date('Y',time()) ?>.  All Rights Reserved</p>
                        <img src="<?= Yii::getAlias('@web/images/payments.png') ?>" alt="payment methods" class="footer-payments">
                    </div><!-- End .footer-bottom -->
                </div><!-- End .col-lg-9 -->
            </div><!-- End .row -->
        </div><!-- End .container-fluid -->
    </div><!-- End .footer-middle -->
</footer><!-- End .footer -->
