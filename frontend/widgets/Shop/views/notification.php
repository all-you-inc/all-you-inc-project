<?php

use shop\helpers\PriceHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use common\modules\notification\models\Notification;
use common\services\NotificationService;
?>
<div id="updateSeen" class="dropdown cart-dropdown">
    <a href="#" class="dropdown-toggle1 menu-gray" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
        <span id="count" class="cart-count1"><?= count($unseen) ?></span>
    </a>
    <?php if ($notifications) { ?>
        <div class="dropdown-menu" style="width: 250px;">
            <div class="notification dropdownmenu-wrapper" style='padding: 0;overflow:auto;height: 400px;width: fit-content;'>
                <!--<div class="notification" style='overflow:auto; height:400px;'>-->
                <?php
                foreach ($notifications as $notification) {
                    $model = $notification->source_class;
                    $source = $model::findOne($notification->source_pk);
                    $originator = $notification->originator;
                    $user = $notification->user;
                    $class = Yii::$container->get($notification->class);
                    $msg = Yii::$app->controller->renderPartial('@common/modules/' . $class->moduleId . '/views/' . $class->viewNameText, ['originator' => $originator, 'user' => $user, 'source' => $source]);
                    $url = '/#';
                    if ($class->notificationEventKey == Notification::Message)
                        $url = '/messages?thread-id=' . $source->thread_id.'&message-id='.$source->id;
                    ?>
                    <a href="<?= Html::encode(Url::to([$url])) ?>">
                        <div class="dropdown-cart-products <?= $notification->seen == 0 ? 'active-noti' : '' ?>">
                            <div class="item col-12">
                                <span class="float-right date"><?php echo $notification->created_at ?></span><br>
                                <?php echo $msg ?>
                            </div>
                        </div>
                    </a>
                    <?php
                }
                ?>
            </div><!-- End .dropdownmenu-wrapper -->
        </div><!-- End .dropdown-menu -->
    <?php } ?>
</div><!-- End .dropdown -->
<script>
    $("#updateSeen").click(function () {
        var request = baseUrl + "/user/updatenoticount";
        $.ajax({url: request,
            data: {},
            type: 'POST',
            success: function (result) {
                if (result) {
                    $("#count").text(0);
                } else {
                    alert('Error occured in updatenoticount function');
                }
            },
            error: function () {
                alert('Error occured in updatenoticount function');
            }
        });
    });
</script>