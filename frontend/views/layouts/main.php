<?php
/* @var $this \yii\web\View */
/* @var $content string */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use frontend\widgets\Shop\CartWidget;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="en" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="en" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="ltr" lang="en">
    <!--<![endif]-->
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <link href="<?= Html::encode(Url::canonical()) ?>" rel="canonical"/>
        <link href="<?= Yii::getAlias('@web/images/logo1.png') ?>" rel="icon"/>
        <?php $this->head() ?>
        <script>var baseUrl = "<?php echo Yii::$app->request->baseUrl; ?>";</script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
    <body class="common-home">
        <?php $this->beginBody() ?>
        <div class="page-wrapper">
            <?= $this->render('shared/menu') ?>
            <main class="main custom-main">
                <?= $content ?>
            </main><!-- End .main -->
            <?= $this->render('shared/footer') ?>
        </div><!-- End .page-wrapper -->
        <?php $this->endBody() ?>
        <script>
            $.ajaxSetup({
                data: <?=
        \yii\helpers\Json::encode([
            \yii::$app->request->csrfParam => \yii::$app->request->csrfToken,
        ])
        ?>
            });
        </script>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
        <?= $this->render('shared/flashmessage') ?>
        <a id="scroll-top" href="#top" title="Top" role="button"><i class="icon-angle-up"></i></a>
    </body>
</html>
<?php $this->endPage() ?>
