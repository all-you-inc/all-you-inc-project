<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\SignupForm */

use yii\helpers\Html;
use kartik\form\ActiveForm;

$this->title = 'Update Profile';
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
                <label>Name </label>
                <input type="text" value="<?= isset($model->name) ? $model->name : '' ?>" name="name" class="form-control" required>
            </div>
            <div class="form-group required-field">
                <label>Phone Number </label>
                <input type="number" value="<?= isset($model->phone) ? $model->phone : '' ?>" name="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label>City </label>
                <input type="text" value="<?= isset($model->city) ? $model->city : '' ?>" name="city" class="form-control">
            </div>
            <div class="form-group">
                <label>State </label>
                <input type="text" value="<?= isset($model->state) ? $model->state : '' ?>" name="state" class="form-control">
            </div>
<!--            <div class="form-group">
                <label>Country </label>
                <input type="text" value="<?= isset($model->country) ? $model->country : '' ?>" name="country" class="form-control">
            </div>-->
<div class="form-group required-field">
    <label>Country </label>
    <div class="select-custom">
        <select class="form-control" required="" name="country">
            <option value="">Please Select Any Country</option>
            <?php
            if ($countries) {
                foreach ($countries as $country) {
                    ?>
                    <option <?= isset($model->country) && $model->country == $country->id ? 'selected' : '' ?> value="<?= $country->id ?>"><?= $country->title ?></option>
                    <?php
                }
            }
            ?>
        </select>
    </div>
</div>
            <div class="form-footer">
                <?= Html::submitButton('Update', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div> 
            <?= Html::endForm() ?>
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