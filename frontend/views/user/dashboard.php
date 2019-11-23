<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;
use shop\helpers\PriceHelper;
use kartik\form\ActiveForm;
use common\models\usertalent\UserTalent;
use common\models\djgenre\DjGenre;
use common\models\membership\Membership;

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
$membership_id = \Yii::$app->user->identity->getUser()->getMembershipId();
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
        <?= $this->render('../layouts/shared/admin_menu', ['active' => 'dashboard', 'url' => Html::encode(Url::to(['/myprofile']))]) ?>
        <div class="col-lg-9 order-lg-last dashboard-content">
            <div class="dashboard-container">
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <?php if ($membership_id != Membership::Promoter) { ?>
                            <div class="select-custom">
                                <select class="form-control" name="talent" onchange="location = this.value;">
                                    <option value="/dashboard">All Talent</option>
                                    <?php foreach (\Yii::$app->user->identity->getUser()->talentList() as $key => $value) { ?>
                                        <option value="/dashboard?talentId=<?= $key ?>" <?= ($key == $talentId) ? "selected" : "" ?>><?= $value ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        <?php } else { ?>
                            <a href="#"><button class="form-control" style="color: green; border: 1pxlightgray;">SHOW YOUR TALENTS</button></a>
                        <?php } ?>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <?php if ($membership_id != Membership::Promoter) { ?>
                            <?php if ($membership_id != Membership::Talent) { ?>
                                <div class="select-custom">
                                    <select class="form-control" name="product" onchange="location = this.value;">
                                        <option value="/dashboard">All Products</option>
                                        <?php
                                        if ($products) {
                                            foreach ($products as $product) {
                                                $value = isset($_GET['talentId']) && $_GET['talentId'] != null ? '/dashboard?talentId=' . $_GET['talentId'] . '&productId=' . $product->id : '/dashboard?productId=' . $product->id;
                                                ?>
                                                <option value="<?= $value ?>" <?= ($product->id == $productId) ? "selected" : "" ?>><?= $product->name ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            <?php } else { ?>
                                <a href="<?= Html::encode(Url::to(['/subscription'])) ?>"><button class="form-control" style="color: green; border: 1pxlightgray;">UPGRADE WITH A PRODUCT</button></a>
                            <?php } ?>
                        <?php } else { ?>
                            <a href="#"><button class="form-control" style="color: green; border: 1pxlightgray;">SELL YOUR PRODUCTS</button></a>
                        <?php } ?>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <?php if ($membership_id != Membership::Promoter) { ?>
                            <a target="_blank" href="<?= Html::encode(Url::to(['/signup'])) ?>" class="color-green"><button class="form-control" style="color: green;border: 1pxlightgray;">Earn $$$ Promoting</button></a>
                        <?php } else { ?>
                            <a href="#"><button class="form-control" style="color: green; border: 1pxlightgray;">PROMOTER</button></a>
                        <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <?php if ($membership_id != Membership::Talent) { ?>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="dashboard-info-box">
                                        <span><i class="fa fa-shopping-cart dashboard-icon"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Orders</span>
                                            <span class="info-box-number"><?= $totalOrders ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="dashboard-info-box">
                                        <span><i class="fa fa-dollar dashboard-icon"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Sales</span>
                                            <span class="info-box-number"><?= PriceHelper::format($totalSales) ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <div class="dashboard-info-box">
                                    <span><i class="fa fa-users dashboard-icon"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Fans</span>
                                        <span class="info-box-number">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <script>
                                $(document).ready(function () {
//                                    new Morris.Line({
//                                        // ID of the element in which to draw the chart.
//                                        element: 'myfirstchart',
//                                        resize: true,
//                                        data: [
//                                            {y: '2011 Q1', item1: 2666},
//                                            {y: '2011 Q2', item1: 2778},
//                                            {y: '2011 Q3', item1: 4912},
//                                            {y: '2011 Q4', item1: 3767},
//                                            {y: '2012 Q1', item1: 6810},
//                                            {y: '2012 Q2', item1: 5670},
//                                            {y: '2012 Q3', item1: 4820},
//                                            {y: '2012 Q4', item1: 15073},
//                                            {y: '2013 Q1', item1: 10687},
//                                            {y: '2013 Q2', item1: 8432}
//                                        ],
//                                        xkey: 'y',
//                                        ykeys: ['item1'],
//                                        labels: ['Item 1'],
//                                        lineColors: ['#efefef'],
//                                        lineWidth: 2,
//                                        hideHover: 'auto',
//                                        gridTextColor: '#fff',
//                                        gridStrokeWidth: 0.4,
//                                        pointSize: 4,
//                                        pointStrokeColors: ['#efefef'],
//                                        gridLineColor: '#efefef',
//                                        gridTextFamily: 'Open Sans',
//                                        gridTextSize: 10
//                                    });
                                    new Morris.Line({
                                        element: 'myfirstchart',
                                        resize: true,
                                        data: [
                                        <?php 
                                        foreach ($activeChart['data'] as $key => $value): 
                                            if ($value): ?>
                                                { y: '<?= $key ?>', a: <?= $value['sales'] ?>,  b: <?= $value['fans'] ?>},
                                            <?php else: ?>
                                                { y: '<?= $key ?>'},
                                            <?php
                                            endif;
                                        endforeach; 
                                        ?>
                                        ],
                                        xkey: 'y',
                                        ykeys: ['a', 'b'],
                                        labels: [
                                        <?php foreach ($activeChart['labels'] as $label): ?>
                                            '<?= $label ?>',
                                        <?php endforeach; ?>
                                        ],
                                        xLabels: '<?= $activeChart['xLabels'] ?>',
                                        lineColors: [
                                        <?php foreach ($activeChart['lineColors'] as $color): ?>
                                            '<?= $color ?>',
                                        <?php endforeach; ?>
                                        ],
                                        lineWidth: 2,
                                        hideHover: 'auto',
                                        gridTextColor: '#fff',
                                        gridStrokeWidth: 0.4,
                                        pointSize: 4,
                                        pointStrokeColors: ['#efefef'],
                                        gridLineColor: '#efefef',
                                        gridTextSize: 10
                                    });

                                    $('#world-map-markers').vectorMap({
                                        map: 'us_aea',
//                                         map: 'world_mill',
                                        normalizeFunction: 'polynomial',
                                        hoverOpacity: 0.7,
                                        hoverColor: false,
                                        backgroundColor: 'transparent',
                                        regionStyle: {
                                            initial: {
                                                fill: 'rgba(210, 214, 222, 1)',
                                                'fill-opacity': 1,
                                                stroke: 'none',
                                                'stroke-width': 0,
                                                'stroke-opacity': 1
                                            },
                                            hover: {
                                                'fill-opacity': 0.7,
                                                cursor: 'pointer'
                                            },
                                            selected: {
                                                fill: 'yellow'
                                            },
                                            selectedHover: {}
                                        },
//                                        markerStyle: {
//                                            initial: {
//                                                fill: '#00a65a',
//                                                stroke: '#111'
//                                            }
//                                        },
                                        markers: [
                                            <?php 
                                            if ($activeMap['sales']['data']):
                                                foreach ($activeMap['sales']['data'] as $customer):
                                                    if ($customer['latitude'] && $customer['longitude']):
                                                    ?>
                                                        {latLng: [<?= floatval($customer['latitude']) ?>, <?= floatval($customer['longitude']) ?>], name: '<?= $customer['name'] ?>', style: {fill: '<?= $activeMap['sales']['color'] ?>', stroke: '<?= $activeMap['sales']['color'] ?>'}},
                                                    <?php
                                                    endif;
                                                endforeach;
                                            endif;
                                            ?>
                                            <?php 
                                            if ($activeMap['fans']['data']):
                                                foreach ($activeMap['fans']['data'] as $fan):
                                                    if ($fan['latitude'] && $fan['longitude']):
                                                    ?>
                                                        {latLng: [<?= floatval($fan['latitude']) ?>, <?= floatval($fan['longitude']) ?>], name: '<?= $fan['name'] ?>', style: {fill: '<?= $activeMap['fans']['color'] ?>', stroke: '<?= $activeMap['fans']['color'] ?>'}},
                                                    <?php
                                                    endif;
                                                endforeach;
                                            endif;
                                            ?>
//                                            {latLng: [37.697948, -97.314835], name: 'Kansas', style: {fill: 'green', stroke: '#111'}},
//                                            {latLng: [46.87, -110.36], name: 'Montana', style: {fill: 'green', stroke: '#111'}},
//                                            {latLng: [40.730610, -73.935242], name: 'Fan 1', style: {fill: '#2B61E7', stroke: '#111'}},
                                            {latLng: [30.274096, -93.732666], name: 'Event 1', style: {fill: 'black', stroke: '#111'}},
                                            {latLng: [42.87, -101.36], name: 'Event 2', style: {fill: 'black', stroke: '#111'}},
                                        ]
                                    });

                                });
                            </script>       
                            <div class="box box-solid bg-teal-gradient">
                                <div class="box-header ui-sortable-handle">
                                    <h3 class="title-chart">My Active Chart</h3>
                                </div>
                                <div id="myfirstchart" style="height: 250px;"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="box bg-teal-gradient1">
                                <div class="box-header with-border">
                                    <h3 class="title-chart">My Active Map</h3>
                                </div>
                                <div class="box-body no-padding">
                                    <div class="row">
                                        <div class="col-md-9 col-sm-8" style="background-color: white;">
                                            <div id="world-map-markers" style="height: 250px;"></div>
                                        </div>
                                        <div class="col-md-3 col-sm-4">
                                            <div class="pad box-pane-right bg-green" style="min-height: 280px">
                                                <div class="description-block margin-bottom">
                                                    <div class="sparkbar pad" data-color="#fff"><canvas width="34" height="30" style="display: inline-block; width: 34px; height: 30px; vertical-align: top;"></canvas></div>
                                                    <span style='font-size: 25px;color: green;'>&#9679;</span>
                                                    <strong><span style='color: green;'>Sales</span></strong>
                                                </div>
                                                <div class="description-block margin-bottom">
                                                    <div class="sparkbar pad" data-color="#fff"><canvas width="34" height="30" style="display: inline-block; width: 34px; height: 30px; vertical-align: top;"></canvas></div>
                                                    <span style='font-size: 25px;color: #2B61E7;'>&#9679;</span>
                                                    <strong><span style='color: #2B61E7;'>Fans</span></strong>
                                                </div>
                                                <div class="description-block margin-bottom">
                                                    <div class="sparkbar pad" data-color="#fff"><canvas width="34" height="30" style="display: inline-block; width: 34px; height: 30px; vertical-align: top;"></canvas></div>
                                                    <span style='font-size: 25px;color: #000;'>&#9679;</span>
                                                    <strong><span style='color: #000;'>Events</span></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="card bg-teal-gradient2">
                                <h3 class="title-calendar">
                                    <i class="far fa-calendar-alt"></i>
                                    Events
                                </h3>
                                <div class="card-body pt-0">
                                    <div id="calendar" style="width: 100%"><div class="bootstrap-datetimepicker-widget usetwentyfour"><ul class="list-unstyled"><li class="show"><div class="datepicker"><div class="datepicker-days" style=""><table class="table table-sm"><thead><tr><th class="prev" data-action="previous"><span class="fa fa-chevron-left" title="Previous Month"></span></th><th class="picker-switch" data-action="pickerSwitch" colspan="5" title="Select Month">August 2019</th><th class="next" data-action="next"><span class="fa fa-chevron-right" title="Next Month"></span></th></tr><tr><th class="dow">Su</th><th class="dow">Mo</th><th class="dow">Tu</th><th class="dow">We</th><th class="dow">Th</th><th class="dow">Fr</th><th class="dow">Sa</th></tr></thead><tbody><tr><td data-action="selectDay" data-day="07/28/2019" class="day old weekend">28</td><td data-action="selectDay" data-day="07/29/2019" class="day old">29</td><td data-action="selectDay" data-day="07/30/2019" class="day old">30</td><td data-action="selectDay" data-day="07/31/2019" class="day old">31</td><td data-action="selectDay" data-day="08/01/2019" class="day">1</td><td data-action="selectDay" data-day="08/02/2019" class="day">2</td><td data-action="selectDay" data-day="08/03/2019" class="day weekend">3</td></tr><tr><td data-action="selectDay" data-day="08/04/2019" class="day weekend">4</td><td data-action="selectDay" data-day="08/05/2019" class="day">5</td><td data-action="selectDay" data-day="08/06/2019" class="day">6</td><td data-action="selectDay" data-day="08/07/2019" class="day">7</td><td data-action="selectDay" data-day="08/08/2019" class="day">8</td><td data-action="selectDay" data-day="08/09/2019" class="day">9</td><td data-action="selectDay" data-day="08/10/2019" class="day weekend">10</td></tr><tr><td data-action="selectDay" data-day="08/11/2019" class="day weekend">11</td><td data-action="selectDay" data-day="08/12/2019" class="day">12</td><td data-action="selectDay" data-day="08/13/2019" class="day">13</td><td data-action="selectDay" data-day="08/14/2019" class="day">14</td><td data-action="selectDay" data-day="08/15/2019" class="day">15</td><td data-action="selectDay" data-day="08/16/2019" class="day">16</td><td data-action="selectDay" data-day="08/17/2019" class="day weekend">17</td></tr><tr><td data-action="selectDay" data-day="08/18/2019" class="day weekend">18</td><td data-action="selectDay" data-day="08/19/2019" class="day">19</td><td data-action="selectDay" data-day="08/20/2019" class="day">20</td><td data-action="selectDay" data-day="08/21/2019" class="day">21</td><td data-action="selectDay" data-day="08/22/2019" class="day">22</td><td data-action="selectDay" data-day="08/23/2019" class="day">23</td><td data-action="selectDay" data-day="08/24/2019" class="day weekend">24</td></tr></tbody></table></div></div></li><li class="picker-switch accordion-toggle"></li></ul></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="dashboard-rightbar-1">
                                <div class="item">
                                    New Order
                                    <span class="number"><?= $totalOrders ?></span>
                                </div>
                                <div class="item">
                                    New fans
                                    <span class="number">0</span>
                                </div>
                                <div class="item">
                                    New Auditions
                                    <span class="number">0</span>
                                </div>
                                <div class="item">
                                    New Fan Share Request
                                    <span class="number">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="dashboard-rightbar-2">
                                <div class="box-header-rightbar-2">
                                    Notifications
                                </div>
                                <div class="item">
                                    John order a book
                                </div>
                                <div class="item">
                                    Martin order a book
                                </div>
                                <div class="item">
                                    Elizebeth become your fan
                                </div>
                                <div class="item">
                                    Steve requested a book
                                </div>
                                <div class="item">
                                    John order a book
                                </div>
                                <div class="item">
                                    Martin order a book
                                </div>
                                <div class="item">
                                    Elizebeth become your fan
                                </div>
                                <div class="item">
                                    Steve requested a book
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>