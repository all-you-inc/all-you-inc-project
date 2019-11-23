<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $category shop\entities\Shop\Category */

use common\models\usertalent\UserTalent;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\userprofileimage\UserProfileImage;
use yii\widgets\LinkPager;

$this->title = 'Talents';
$this->params['breadcrumbs'][] = $this->title;
?>
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container-fluid ">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= \yii\helpers\Url::home() ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
            </ol>
        </div><!-- End .container-fluid  -->
    </nav>

    <div class="container-fluid spotlight-main">
        <center>
            <form action="/catalog/search" method="GET">
                <div class="header-search-wrapper">
                    <span><input type="search" value="<?= isset($_GET['query']) && $_GET['query'] != '' ? $_GET['query'] : '' ?>" class="form-control" style="display: inherit;"name="query" id="q" placeholder="Search Talent..."></span>
                    <button class="btn search-custom-margin" type="submit"><i class="icon-search"></i></button>
                    <input type="text" hidden="" name="category" value="talent">
                    <nav class="toolbox">

                        <div class="toolbox-item toolbox-show">
                            <!-- Search Area -->
                        </div><!-- End .toolbox-item -->
                        <ul class="pagination">
                            <?=
                            LinkPager::widget([
                                'pagination' => $pages,
                                'activePageCssClass' => 'page-item active',
                                'maxButtonCount' => 8,
                                'linkOptions' => ['class' => 'page-item page-link'],
                                'disabledPageCssClass' => 'disabled',
                            ]);
                            ?>
                        </ul>
                    </nav>

                </div>
            </form>
            <div class="container">
                <?php
                if ($dataProvider) {
                    foreach ($dataProvider as $item) {
                        $url = Url::to(['user/portfolio', 'id' => $item->id]);
                        ?>
                        <a href="<?= Html::encode($url) ?>">
                            <div class="col-md-3">
                                <div class="card profile-card-3">
                                    <div class="background-block">
                                        <?php $imageprofile = UserProfileImage::getProfileImage($item->user->id);
                                        ?>
                                        <img src="<?= $imageprofile == NULL ? Yii::getAlias('@web/images/spotlight/profile-1.png') : $imageprofile ?>" alt="profile-image" class="sidemenu-profile-img"/>
                                    </div>
                                    <div class="card-content">
                                        <h2><?= $item->user->name ?></h2>
                                        <h4 ><?= ( $item->talent->name); ?></h4>
                                        <h3 class="fan">Fans: 1.99M</h3>
                                        <?php if (isset($item->user->country) && $item->user->country != ''): ?>
                                            <h4 class="margin-bottom-auto">From: <?= $item->user->country ?></h4>
                                        <?php endif; ?> 
                                    </div>

                                </div>
                            </div>
                        </a>
                        <?php
                    }
                }
                ?>
            </div>
            <nav class="toolbox">

                <div class="toolbox-item toolbox-show">
                    <!-- Search Area -->
                </div><!-- End .toolbox-item -->
                <ul class="pagination">
                    <?=
                    LinkPager::widget([
                        'pagination' => $pages,
                        'activePageCssClass' => 'page-item active',
                        'maxButtonCount' => 8,
                        'linkOptions' => ['class' => 'page-item page-link'],
                        'disabledPageCssClass' => 'disabled',
                    ]);
                    ?>
                </ul>
            </nav>
        </center>
    </div><!-- End .container-fluid -->
</main><!-- End .main -->

