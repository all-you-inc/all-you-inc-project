<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php if (Yii::$app->session->hasFlash('success') || Yii::$app->session->hasFlash('error')) { ?>
    <div class="newsletter-popup mfp-hide" id="newsletter-popup-form" style="background-image: url(<?= Yii::getAlias('@web/images/newsletter_popup.jpg') ?>">
        <div class="newsletter-popup-content">
            <img src="<?= Yii::getAlias('@web/images/logo.png') ?>" alt="All You Media" class="logo-newsletter">
            <?php
            if (Yii::$app->session->hasFlash('success')) {
                $messages = Yii::$app->session->getFlash('success');
                if (is_array($messages)) {
                    ?>
            <h4 class="flash-heading-success"> <i class="icon-ok-sign"></i> Success! </h4>
                    <?php foreach ($messages as $message) {
                        ?>
                        <p class="flash-message"><?php echo $message; ?></p>
                        <?php
                    }
                } else {
                    ?>
                    <h4 class="flash-heading-success"> <i class="icon-ok-sign"></i> Success! </h4>
                    <p class="flash-message"><?php echo $messages; ?></p>
                    <?php
                }
            } elseif (Yii::$app->session->hasFlash('error')) {
                $messages = Yii::$app->session->getFlash('error');
                if (is_array($messages)) {
                    ?>
                    <h4 class="flash-heading-error"> <i class="icon-remove-sign"></i> Error! </h4>
                    <?php foreach ($messages as $message) {
                        ?>
                        <p class="flash-message"><?php echo $message; ?></p> 
                        <?php
                    }
                } else {
                    ?>
                    <h4 class="flash-heading-error"> <i class="icon-remove-sign"></i> Error! </h4>
                    <p class="flash-message"><?php echo $messages; ?></p>
                    <?php
                }
            }
            ?>
        </div>
    </div>
<?php } ?>