<?php

namespace common\services;

use Yii;
use common\modules\notification\models\Notification;
use common\models\userdevice\UserDevice;
use shop\entities\User\User;
use common\models\notification\NotificationEvents;
use common\models\notification\UserNotification;

class NotificationService {

    public static function postUserNotificationEvents($data, $user_id) {
        UserNotification::deleteAll(['user_id' => $user_id]);
        if ($data) {
            foreach ($data as $id => $items) {
                $model = new UserNotification();
                $model->user_id = $user_id;
                $model->notification_event_id = $id;
                foreach ($items as $event => $item) {
                    $model->$event = TRUE;
                }
                $model->created_at = time();
                $model->created_by = $user_id;
                $model->modified_at = time();
                $model->modified_by = $user_id;
                $model->save();
            }
            return TRUE;
        }
        return FALSE;
    }

    public static function getAllNotificationEvents() {
        return NotificationEvents::findAll(['is_deleted' => 0]);
    }

    public static function getAllUserNotificationEvents($user_id) {
        return UserNotification::findAll(['is_deleted' => 0, 'user_id' => $user_id]);
    }

    public static function getUserNotifications($user_id, $limit = 30) {
        return Notification::find()->where(['user_id' => $user_id])->limit($limit)->orderBy(['id' => SORT_DESC])->all();
    }

    public static function getUserUnseenNotifications($user_id) {

        date_default_timezone_set(\Yii::$app->params['timezone']);
        $to = date('Y-m-d H:i:s', time());
        $from = 0;
        if (!Yii::$app->user->isGuest)
            $from = date('Y-m-d H:i:s', \Yii::$app->user->identity->getUser()->notification_seen_at);
        
        return Notification::find()->where(['user_id' => $user_id])->andWhere(['between', 'created_at', $from, $to])->all();
    }

    public static function updateUserNotificationSeens() {
        $user = \Yii::$app->user->identity->getUser();
        $user->notification_seen_at = time();
        if ($user->update()) {
            return TRUE;
        }
        return FALSE;
    }

    public static function updateNotificationSeenBySource($user_id, $class, $pk) {
        return Notification::updateAll(['seen' => 1], ['user_id' => $user_id, 'source_class' => $class, 'source_pk' => $pk]);
    }

    public static function getUserNotificationEvents($user_id) {
        if ($user_id) {
            $user = User::findOne($user_id);
            $events = ['email' => FALSE, 'web' => FALSE, 'mobile' => FALSE];
            if ($user instanceof User) {
                if ($user->notification_events) {
                    foreach (explode(',', $user->notification_events) as $event) {
                        if (array_key_exists($event, $events))
                            $events[$event] = TRUE;
                    }
                }
            }
            return $events;
        }
        return False;
    }

    public static function getUserDevices($user_id, $type = NULL, $notification = NULL) {
        $devices = UserDevice::find()->where(['user_id' => $user_id]);
        if ($type)
            $devices = $devices->andWhere(['type' => $type]);
        if ($notification)
            $devices = $devices->andWhere(['notification' => $notification]);
        return $devices->all();
    }

}
