            <div class="home-slider-container">
                <div class="home-slider owl-carousel owl-theme">
                  

                    <div class="home-slide">
                        <div class="slide-bg owl-lazy"  data-src="<?= Yii::getAlias('@web/images/slider/slide-2.png') ?>"></div><!-- End .slide-bg -->
                    </div><!-- End .home-slide -->

                    
                </div><!-- End .home-slider -->
            </div><!-- End .home-slider-container -->
            <?php if (Yii::$app->user->isGuest): ?>
                <div class="container-fluid" style="background-color:#6cc9f4;">
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-xs-5 col-sm-4 col-centered">
                        <a href="<?= yii\helpers\Html::encode(yii\helpers\Url::to(['/signup'])) ?>" class="btn signup-btn" style="width:150px;margin:20px;">Join Now</a>
                        </div>
                    </div>   
                </div>  
            <?php endif; ?>

