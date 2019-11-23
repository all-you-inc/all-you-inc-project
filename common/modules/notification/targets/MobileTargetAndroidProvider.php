<?php

namespace common\modules\notification\targets;

use common\modules\notification\components\BaseNotification;
use shop\entities\User\User;
//use shop\services\TransactionManager;

class MobileTargetAndroidProvider implements MobileTargetProvider {

//    private $transaction;
//
//    public function __construct($id, $module, TransactionManager $transaction, $config = []) {
//        parent::__construct($id, $module, $config);
//        $this->transaction = $transaction;
//    }

    /**
     * @param BaseNotification $notification
     * @param User $user
     * @return boolean
     */
    public function handle($device_token, $msg = null, $title = null, $pk = null, $key, $params = []) {
        d('---In Android Push---');
//      $device_token = "cnE3SO3e2Ps:APA91bFtwKeeIFo60uwgDWk_9VTAFxehCAGg3yglrO0zeeCEf8-QSRy2oi3qUiiE59NQ-0q_HuHWZs_gmFKTsdo40E1xQd0_Jtw4GTI0P68NpTfD3JJP9Sd4RNkYqttDp2A8eVDUSEou1";
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . \Yii::$app->params['fcm']['apiKey']
        );
        $data = array(
            'to' => $device_token,
            'notification' => ['title' => $title, 'body' => $msg],
            'data' => ['notificationExperienceUrl' => \Yii::$app->params['fcm']['notificationExperienceUrl'],
                'title' => $title,
                'message' => $msg,
                'body' => ["msg" => $msg, "pk" => $pk, "key" => $key],
                'experienceId' => \Yii::$app->params['fcm']['experienceId'],
                'notificationId' => -1,
                'isMultiple' => false,
                'remote' => true,
                'notification_object' => ['title' => $title,
                    'experienceId' => \Yii::$app->params['fcm']['experienceId'],
                    'notificationId' => -1,
                    'isMultiple' => false,
                    'remote' => true,
                    "data" => ["msg" => $msg, "pk" => $pk, "key" => $key]
        ]]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, \Yii::$app->params['fcm']['link']);
        if ($headers)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($response, true);
        if ($res['failure']) {
            d('Some thing went wrong..');
//            $this->transaction->wrap(function () use ($device_token) {
                \common\models\userdevice\UserDevice::deleteAll('token="' . $device_token . '"');
//            });
//            exit();
        }
        d('Successfully send push to Android');
//        exit();
    }

    /**
     * @param User|null $user
     * @return boolean
     */
//    public function isActive(User $user = null);
}
