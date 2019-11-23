<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\SignupForm */

use yii\helpers\Html;
use kartik\form\ActiveForm;

$this->title = 'Add Talent';
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
        <div class="center col-md-5">
            <div class="heading">
                <h2 class="title"><?= $this->title ?></h2>
            </div>
            <?= Html::beginForm() ?>
            <div class="form-group required-field">
                <label>Whats your Industry</label>
                <div class="select-custom">
                    <select class="form-control" required="" name="industry_id" id="selected-industry">
                        <option value="">Please Select Any Industry</option>
                        <?php foreach ($industries as $industry) { ?>
                            <option value="<?= $industry->id ?>"><?= $industry->name ?></option>
                        <?php } ?>
                    </select>
                </div><!-- End .select-custom -->
            </div><!-- End .form-group -->
            <div class="form-group required-field" id="talent">
                <label>Whats your Talent</label>
                <div class="select-custom" id="talent-dd">
                    <select class="form-control" required="" name="talent_id" onchange="talent(this.value)" id="selected-talent">
                        <option value="">Please Select Any Talent</option>
                    </select>
                </div><!-- End .select-custom -->
            </div><!-- End .form-group -->
            <div class="form-group" id="gender">
                <label>Whats your Gender</label>
                <div class="select-custom">
                    <select class="form-control" name="gender" id="selected-gender">
                        <option value="">Please Select Any Gender</option>
                        <option value="female">Female</option>
                        <option value="male">Male</option>
                        <option value="other">Others</option>
                    </select>
                </div><!-- End .select-custom -->
            </div><!-- End .form-group -->
            <div class="form-group" id="group_gender">
                <label>Whats your Gender</label>
                <div class="select-custom">
                    <select class="form-control" name="group_gender" id="selected-group_gender">
                        <option value="">Please Select Any Gender</option>
                        <option value="co-ed">CO-ED</option>
                        <option value="all-female">All Female</option>
                        <option value="all-male">All Male</option>
                        <option value="other">Others</option>
                    </select>
                </div><!-- End .select-custom -->
            </div><!-- End .form-group -->
            <div class="form-group" id="dj_genre">
                <label>Whats your Dj Genre</label>
                <div class="select-custom" id="dj_genre-dd">
                    <select class="form-control" name="dj_genre_id" id="selected-dj_genre">
                        <option value="">Please Select Any Dj Genre</option>
                    </select>
                </div><!-- End .select-custom -->
            </div><!-- End .form-group -->
            <div class="form-group" id="instrument">
                <label>Whats your Instruments</label>
                <div class="select-custom" id="instrument-dd">
                    <select class="form-control" name="instrument_id" onchange="getinstrumentspec(this.value)" id="selected-instrument">
                        <option value="">Please Select Any Instrument</option>
                    </select>
                </div><!-- End .select-custom -->
            </div><!-- End .form-group -->
            <div class="form-group" id="instrument_spec">
                <label>Whats your Specification</label>
                <div class="select-custom" id="instrument_spec-dd">
                    <select class="form-control" name="instrument_spec_id" id="selected-instrument_spec">
                        <option value="">Please Select Any Specification</option>
                    </select>
                </div><!-- End .select-custom -->
            </div><!-- End .form-group -->
            <div class="form-group" id="music_genre">
                <label>Whats your Music Genre</label>
                <div class="select-custom" id="music_genre-dd">
                    <select class="form-control" name="music_genre_id" id="selected-music_genre">
                        <option value="">Please Select Any Music Genre</option>
                    </select>
                </div><!-- End .select-custom -->
            </div><!-- End .form-group -->
            <div class="form-footer">
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'signup-button', 'title' => 'Complete Your Bio Info']) ?>
                <?= Html::endForm() ?>  
                <strong>OR</strong> 
                <div class="login-signup-btn-padding">
                    <a href="<?= \yii\helpers\Url::home() ?>" class="paction product-promote-btn login-signup-btn btn-secondary" title="Go to your Dashboard">SKIP</a>
                </div> 
            </div> 

        </div>
    </div>
    <div class="mb-5"></div><!-- margin -->
</main><!-- End .main -->

<script>
    $(document).ready(function () {
        $("#talent").hide();
        $("#gender").hide();
        $("#group_gender").hide();
        $("#dj_genre").hide();
        $("#instrument").hide();
        $("#instrument_spec").hide();
        $("#music_genre").hide();
        $("#selected-industry").change(function () {
            $("#talent").hide();
            $("#gender").hide();
            $("#group_gender").hide();
            $("#dj_genre").hide();
            $("#instrument").hide();
            $("#instrument_spec").hide();
            $("#music_genre").hide();
            gettalent($(this).val());
        });
    });



    function talent(id) {
        $("#gender").hide();
        $("#group_gender").hide();
        $("#instrument").hide();
        $("#instrument_spec").hide();
        var industry_id = $("#selected-industry").val();
        if (industry_id == 6) { //dj_genre
            getdjgenre(industry_id);
        }
        if (industry_id == 15) { //INDUSTRY->MUSIC
            getmusicgenderstatus(id);
        } else {
            getgenderstatus(industry_id);
        }
    }

    function gettalent(id) {
        var request = baseUrl + "/auth/signup/gettalent";
        $.ajax({url: request,
            data: {id: id},
            type: 'POST',
            success: function (result) {
                $('#talent').show();
                $('#talent-dd').html(result);
            },
            error: function () {
                alert('Error occured');
            }
        });
    }

    function getdjgenre(id) {
        var request = baseUrl + "/auth/signup/getdjgenre";
        $.ajax({url: request,
            data: {id: id},
            type: 'POST',
            success: function (result) {
                $("#dj_genre").show();
                $('#dj_genre-dd').html(result);
            },
            error: function () {
                alert('Error occured');
            }
        });
    }

    function getmusic_genre() {
        var request = baseUrl + "/auth/signup/getmusic_genre";
        $.ajax({url: request,
            type: 'POST',
            success: function (result) {
                $("#music_genre").show();
                $('#music_genre-dd').html(result);
            },
            error: function () {
                alert('Error occured');
            }
        });
    }

    function getinstrumentspec(id) {
        var request = baseUrl + "/auth/signup/getinstrumentspec";
        $.ajax({url: request,
            data: {id: id},
            type: 'POST',
            success: function (result) {
                $("#instrument_spec").show();
                $('#instrument_spec-dd').html(result);
                getmusic_genre();
            },
            error: function () {
                alert('Error occured');
            }
        });
    }

    function getinstrument() {
        var request = baseUrl + "/auth/signup/getinstrument";
        $.ajax({url: request,
            type: 'GET',
            success: function (result) {
                $("#instrument").show();
                $('#instrument-dd').html(result);
            },
            error: function () {
                alert('Error occured');
            }
        });
    }

    function getgenderstatus(id) {
        var request = baseUrl + "/auth/signup/getgenderstatus";
        $.ajax({url: request,
            data: {id: id},
            type: 'POST',
            success: function (result) {
                if (result) {
                    $("#gender").show();
                } else {
                    $("#gender").hide();
                }
            },
            error: function () {
                alert('Error occured');
            }
        });
    }

    function getmusicgenderstatus(id) {
        var request = baseUrl + "/auth/signup/getmusicgenderstatus";
        $.ajax({url: request,
            data: {id: id},
            type: 'POST',
            success: function (result) {
                if ((result == 1) || (result == 2)) {
                    getinstrument();
                    if (result == 1) {
                        $("#gender").show();
                    } else if (result == 2) {
                        $("#group_gender").show();
                    }
                } else {
                    $("#gender").hide();
                    $("#group_gender").hide();
                    $("#instrument").hide();
                    $("#instrument_spec").hide();
                    getmusic_genre();
                }
            },
            error: function () {
                alert('Error occured');
            }
        });
    }

</script>