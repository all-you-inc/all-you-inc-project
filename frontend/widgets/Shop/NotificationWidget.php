<?php

namespace frontend\widgets\Shop;

use shop\cart\Cart;
use yii\base\Widget;
use common\modules\notification\models\Notification;
use common\services\NotificationService;

class NotificationWidget extends Widget {

    public function run() {
        return $this->render('notification', [
                    'notifications' => NotificationService::getUserNotifications(\Yii::$app->user->id),
                    'unseen' => NotificationService::getUserUnseenNotifications(\Yii::$app->user->id)
        ]);
    }

}
