<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>
<footer class="footer custom-footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-bottom">
                    <p class="footer-copyright">All You Media &copy;  <?= date('Y', time()) ?>.  All Rights Reserved</p>
                    <p class="align-center">Designed & Developed By <strong><a target="_blank" href="https://siliconplex.com/">Siliconplex</a></strong></p>
                    <img src="<?= Yii::getAlias('@web/images/payments.png') ?>" alt="payment methods" class="footer-payments_">
                </div><!-- End .footer-bottom -->
                <div class="footer-bottom">
                    <p class="footer-copyright">
                        <a href="<?= Url::to(['/contact']); ?>">Contact Us</a>
                        &nbsp;|&nbsp; 
                        <a href="<?= Url::to(['site/about']); ?>">ABOUT US</a> 
                        &nbsp;|&nbsp; 
                        <a href="<?= Url::to(['site/policy']); ?>">PRIVACY POLICY</a>                        
                        &nbsp;|&nbsp; 
                        <a href="<?= Url::to(['site/terms']); ?>">TERMS & CONDITIONS</a>
                    </p>                    
                </div>
            </div><!-- End .col-lg-9 -->
        </div><!-- End .row -->
    </div><!-- End .container-fluid -->
</footer><!-- End .footer -->
