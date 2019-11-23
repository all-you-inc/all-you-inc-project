<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\SignupForm */

use common\models\usertalent\UserTalent;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\userprofileimage\UserProfileImage;
use yii\widgets\LinkPager;

$this->title = 'Gallery';
$this->params['breadcrumbs'][] = $this->title;
?>

<link href="//vjs.zencdn.net/7.6.0/video-js.css" rel="stylesheet">

<!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
<script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>

<main class="main">
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= \yii\helpers\Url::home() ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
        </ol>
    </div><!-- End .container-fluid -->
</nav>
<div class="container">
    <?= $this->render('../layouts/shared/admin_menu', ['active' => 'gallery', 'url' => Html::encode(Url::to(['/gallery']))]) ?>
    <div class="col-lg-19 order-lg-last dashboard-content">
        <div style="display: table;">
            <div class="margin-bottom-20">
                <h2 class="display-inline-block">PHOTOS</h2>
                <form class="margin-bottom-20 cus-upload-form" id="imagefileupload" action="<?= yii\helpers\Url::to(['user/upload']); ?>" method="POST" enctype="multipart/form-data"> 
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                    <a href="#" onclick="javascript:$('#imagefileupload input').click();" class="show-on-custom-link" aria-label="Upload banner">
                        <div class="btn btn-primary float-right">UPLOAD PHOTO</div>
                    </a>
                    <input type="file" name="PhotosForm[files]" id="filesimage" style="display:none;">
                    <input type="text" name="key" value="PHOTOS" style="display:none;">      
                    <input type="text" name="showon" value="<?= UserProfileImage::$show_on_image_gallery ?>" style="display:none;">      
                </form>
            </div>
            <?php
            if ($dataProvider) {
                foreach ($dataProvider as $key => $item) {
//                    dd();
                    ?>
                    <div class="col-md-3">
                        <div class="card profile-card-3">
                            <?php if ($item->is_locked == 1) { ?>
                                <div style="color: #e7e761 !important;z-index: 1;" class="close cursor">&#128274</div>
                                <img src="<?= UserProfileImage::getGalleryImagePath($item) ?>" alt="gallery-image" class="hover-shadow cursor gallery-img blur-img"/>
                            <?php } else { ?>
                                <div style="color: #e7e761 !important;z-index: 1;" class="close cursor" onclick="deleteGallery(<?= $item->id ?>)">&times;</div>
                                <img src="<?= UserProfileImage::getGalleryImagePath($item) ?>" alt="gallery-image" onclick="openModal();
                                        currentSlide(<?= $key + 1 ?>)" class="hover-shadow cursor gallery-img"/>
                                 <?php } ?>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="col-md-3">
                    <div class="card profile-card-3">
                        <div style="color: #e7e761 !important;z-index: 1;width: 50%;padding: 25px 0;font-size: larger;" class="close cursor">DEMO IMAGE</div>
                        <img src="<?= Yii::getAlias('@web/images/spotlight/profile-1.png') ?>" alt="gallery-image" class="hover-shadow cursor gallery-img blur-img"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card profile-card-3">
                        <div style="color: #e7e761 !important;z-index: 1;width: 50%;padding: 25px 0;font-size: larger;" class="close cursor">DEMO IMAGE</div>
                        <img src="<?= Yii::getAlias('@web/images/spotlight/profile-1.png') ?>" alt="gallery-image" class="hover-shadow cursor gallery-img blur-img"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card profile-card-3">
                        <div style="color: #e7e761 !important;z-index: 1;width: 50%;padding: 25px 0;font-size: larger;" class="close cursor">DEMO IMAGE</div>
                        <img src="<?= Yii::getAlias('@web/images/spotlight/profile-1.png') ?>" alt="gallery-image" class="hover-shadow cursor gallery-img blur-img"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card profile-card-3">
                        <div style="color: #e7e761 !important;z-index: 1;width: 50%;padding: 25px 0;font-size: larger;" class="close cursor">DEMO IMAGE</div>
                        <img src="<?= Yii::getAlias('@web/images/spotlight/profile-1.png') ?>" alt="gallery-image" class="hover-shadow cursor gallery-img blur-img"/>
                    </div>
                </div>
            <?php }
            ?>
            <div class="col-md-12">
                <ul class="pagination custom-pagination">
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
            </div>
        </div>
        <div style="display: flow-root;">
            <div class="margin-bottom-20">
                <h2 class="display-inline-block">VIDEOS</h2>
                <form class="cus-upload-form margin-bottom-20" id="videofileupload" action="<?= yii\helpers\Url::to(['user/upload']); ?>" method="POST" enctype="multipart/form-data"> 
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                    <a href="#" onclick="javascript:$('#videofileupload input').click();" class="show-on-custom-link" aria-label="Upload banner">
                        <div class="btn btn-primary">UPLOAD VIDEO</div>
                    </a>
                    <input type="file" name="PhotosForm[files]" id="filesvideo" style="display:none;">    
                    <input type="text" name="key" value="VIDEOS" style="display:none;">      
                    <input type="text" name="showon" value="<?= UserProfileImage::$show_on_video_gallery ?>" style="display:none;">      
                </form>
            </div>
            <?php
            if ($videos) {
                foreach ($videos as $item) {
                    ?>
                    <div class="col-md-4">
                        <div class="card profile-card-3">
                            <?php if ($item->is_locked == 1) { ?>
                                <div style="color: #e7e761 !important;z-index: 1;" class="close cursor">&#128274</div>
                                <!--                                <video class="gallery-img blur-img">
                                                                    <source src="<?= UserProfileImage::getGalleryVideoPath($item) ?>" type="video/<?= $item->image_extension ?>">
                                                                </video>-->
                                <video id='my-video' class='video-js gallery-img blur-img' controls data-setup='{}'>
                                    <source src='<?= UserProfileImage::getGalleryVideoPath($item) ?>' type='video/<?= $item->image_extension ?>'>
                                </video>
                            <?php } else { ?>
                                <div style="color: #e7e761 !important;z-index: 1;" class="close cursor" onclick="deleteGallery(<?= $item->id ?>)">&times;</div>
                                <!--                                <video class="gallery-img" controls>
                                                                    <source src="<?= UserProfileImage::getGalleryVideoPath($item) ?>" type="video/<?= $item->image_extension ?>">
                                                                </video>-->
                                <video id='my-video' class='video-js gallery-img' controls data-setup='{}'>
                                    <source src='<?= UserProfileImage::getGalleryVideoPath($item) ?>' type='video/<?= $item->image_extension ?>'>
                                </video>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div><!-- End .container-fluid -->
</main><!-- End .main -->
<script>
    $('#filesimage').change(function () {
        // submit the form 
        $('#imagefileupload').submit();
    });
    $('#filesvideo').change(function () {
        // submit the form 
        $('#videofileupload').submit();
    });

    function deleteGallery(id) {
        var result = confirm("Want to delete?");
        if (result) {
            var request = baseUrl + "/user/deletegallery";
            $.ajax({url: request,
                data: {id: id},
                type: 'GET',
                success: function (result) {
//                    alert('Successfully deleted');
                    location.reload();
                },
                error: function () {
//                    alert('Error occured');
                }
            });
        }

    }

</script>

<div id="myModal" class="modal">
    <span class="close cursor" onclick="closeModal()">&times;</span>
    <div class="modal-content">
        <?php
        if ($dataProvider) {
            $total_count = count($dataProvider);
            foreach ($dataProvider as $key => $item) {
                ?>
                <div class="mySlides">
                    <div class="numbertext"><?= $key + 1 ?> / <?= $total_count ?></div>
                    <img src="<?= UserProfileImage::getGalleryImagePath($item) ?>" style="width:100%;height: -webkit-fill-available;">
                </div>
                <?php
            }
        }
        ?>
        <a class="prev1" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next1" onclick="plusSlides(1)">&#10095;</a>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById("myModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("myModal").style.display = "none";
    }

    var slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("demo");
        var captionText = document.getElementById("caption");
        if (n > slides.length) {
            slideIndex = 1
        }
        if (n < 1) {
            slideIndex = slides.length
        }
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
//            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex - 1].style.display = "block";
//        dots[slideIndex - 1].className += " active";
//        captionText.innerHTML = dots[slideIndex - 1].alt;
    }
</script>

<script src="//vjs.zencdn.net/7.6.0/video.js"></script>