<?php

namespace common\modules\notification\targets;

use common\modules\notification\components\BaseNotification;
use shop\entities\User\User;
use common\services\JWT;
//use shop\services\TransactionManager;

class MobileTargetIosProvider implements MobileTargetProvider {

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
    public function handle($device_token, $msg = null, $title = null, $pk, $key, $params = []) {
        d('---In Ios Push---');
//        $device_token = '2133d49c15f96ad4e5e861ca16591f513fac5d62ae8f5087a65bda5abf5e2b84';
        $ar_msg['dev_token'] = $device_token;
        $ar_msg['title'] = $title;
        $ar_msg['msg'] = $msg;
        $ar_msg['key'] = $key;
        $ar_msg['pk'] = $pk;
        $authKey = \Yii::$app->getBasePath() . \Yii::$app->params['apns']['authKey'];
        $arParam['teamId'] = \Yii::$app->params['apns']['teamId']; // Get it from Apple Developer's page
        $arParam['authKeyId'] = \Yii::$app->params['apns']['authKeyId'];
        $arParam['apns-topic'] = \Yii::$app->params['apns']['apns-topic'];
        $arClaim = ['iss' => $arParam['teamId'], 'iat' => time()];
        $arParam['p_key'] = file_get_contents($authKey);
        $arParam['header_jwt'] = JWT::encode($arClaim, $arParam['p_key'], $arParam['authKeyId'], 'RS256');
        // Sending a request to APNS
        $stat = $this->push_to_apns($arParam, $ar_msg);
        if ($stat == FALSE) {
            // err handling
            d('Some thing went wrong..');
//            $this->transaction->wrap(function () use ($device_token) {
                \common\models\userdevice\UserDevice::deleteAll('token="' . $device_token . '"');
//            });
        }
        d('Successfully send push to Ios');
    }

    function push_to_apns($arParam, $ar_msg) {
        $arSendData = array();
        $url_cnt = \Yii::$app->params['apns']['url_cnt'];
        $arSendData['aps']['alert']['title'] = $ar_msg['title']; // Notification title
        $arSendData['aps']['alert']['body'] = $ar_msg['msg']; // body text
        $arSendData['body']['title'] = $ar_msg['title']; // Notification title
        $arSendData['body']['msg'] = $ar_msg['msg']; // Notification title
        $arSendData['body']['pk'] = $ar_msg['pk']; // body text
        $arSendData['body']['key'] = $ar_msg['key']; // body text
        $sendDataJson = json_encode($arSendData);
        $endPoint = \Yii::$app->params['apns']['endPoint']; // https://api.push.apple.com/3/device
        $ar_request_head[] = sprintf("content-type: application/json");
        $ar_request_head[] = sprintf("authorization: bearer %s", $arParam['header_jwt']);
        $ar_request_head[] = sprintf("apns-topic: %s", $arParam['apns-topic']);
        $url = sprintf("%s/%s", $endPoint, $ar_msg['dev_token']);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sendDataJson);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $ar_request_head);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpcode != 200) {
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * @param User|null $user
     * @return boolean
     */
}
