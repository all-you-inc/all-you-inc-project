<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\SignupForm */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use common\models\membership\Membership;

$this->title = 'Select Membership Plan';
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
        <ul class="checkout-progress-bar">
            <li class="active">
                <span>Plan Select</span>
            </li>
            <li class="active">
                <span>Review &amp; Payments</span>
            </li>
        </ul>
    </div>
    <?= Html::beginForm() ?>
    <div class="container">
        <div class="center col-md-12">
            <div class="plan-main">
                <div class="container">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <?php
                        foreach ($plans as $key => $plan) {
                            ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="pricingTable <?= $color['class'] ?>">
                                    <svg x="0" y="0" viewBox="0 50 360 220"> 
                                    <!--//50 for height settings-->
                                    <g>
                                    <path fill="<?= $color['code'] ?>" d="M0.732,193.75c0,0,29.706,28.572,43.736-4.512c12.976-30.599,37.005-27.589,44.983-7.061
                                          c8.09,20.815,22.83,41.034,48.324,27.781c21.875-11.372,46.499,4.066,49.155,5.591c6.242,3.586,28.729,7.626,38.246-14.243
                                          s27.202-37.185,46.917-8.488c19.715,28.693,38.687,13.116,46.502,4.832c7.817-8.282,27.386-15.906,41.405,6.294V0H0.48
                                          L0.732,193.75z">
                                    </path>
                                    </g>
                                    <?php
                                    if ($membership_id == 1) {
                                        if ($key == 0)
                                            echo '<text transform="matrix(1 0 0 1 140 110)" fill="#fff" font-size="40">' . explode("-", $plan->title)[0] . '</text>';
                                        else
                                            echo '<text transform="matrix(1 0 0 1 120 110)" fill="#fff" font-size="40">' . $plan->title . '</text>';
                                    }
                                    if ($membership_id == 2)
                                        if ($key == 0)
                                            echo '<text transform="matrix(1 0 0 1 150 110)" fill="#fff" font-size="30">' . explode("-", $plan->title)[0] . '</text>';
                                        else
                                            echo '<text transform="matrix(1 0 0 1 20 110)" fill="#fff" font-size="30">' . $plan->title . '</text>';
                                    if ($membership_id == 3)
                                        echo '<text transform="matrix(1 0 0 1 80 110)" fill="#fff" font-size="40">' . $plan->title . '</text>';
                                    ?>                                                                                                                                            
                                    </svg>
                                    <div class="pricing-content">
                                        <ul class="pricing-content">
                                            <?php
                                            if ($membership_id == 1 || $membership_id == 2) {
                                                echo $plan->description1;
                                            } else {
                                                echo $plan->description;
                                            }
                                            ?>
                                        </ul>
                                        <?php
                                        if ($membership_id == 1 || $membership_id == 2) {
                                            if ($key == 0)
                                                echo '<p><strong>FREE ACCOUNT</strong></p>';
                                            else
                                                echo ' <p><strong>ONLY $' . $plan->price . ' per month</strong></p>';
                                        }else {
                                            echo ' <p><strong>ONLY $' . $plan->price . ' per month</strong></p>';
                                        }
                                        ?>

                                        <?= Html::submitButton('Next', ['class' => 'btn pricingTable-signup', 'name' => 'plan_id', 'value' => $plan->id]) ?>
                                        <?php
                                        if ($key == 0)
                                            echo '<p></p>';
                                        else
                                            echo '<p><strong>SPECIAL DISCOUNT 60 DAYS OF FREE TRIAL</strong></p>';
                                        ?>


                                    </div>
                                </div>
                            </div>
<?php } ?>
                        <div class="col-md-2"></div>
                    </div>
                </div>
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