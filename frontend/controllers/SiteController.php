<?php

namespace frontend\controllers;

use Yii;
use yii\web\Session;
use yii\web\Controller;
use common\models\usertalent\UserTalent;
use common\services\UserReferralService;
use common\services\SquarePaymentService;
use common\models\usersquareinfo\UsersSquareInfo;
use shop\entities\User\User;
use common\modules\customnotification\components\OrderPlace;
use common\modules\customnotification\components\MessageNotification;
use shop\entities\Shop\Product\Product;
use common\components\behaviors\AccessControl;
// use common\components\Controller;
use common\modules\notification\models\forms\FilterForm;
use common\modules\notification\models\Notification;
// use Yii;
use yii\data\Pagination;
use yii\db\IntegrityException;
use \common\models\chat\Message;
use common\services\JWT;
use shop\services\TransactionManager;

/**
 * Site controller
 */
class SiteController extends Controller {

    private $transaction;

    public function __construct($id, $module, TransactionManager $transaction, $config = []) {
        parent::__construct($id, $module, $config);
        $this->transaction = $transaction;
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionAndroidPush() {
        echo '---In Android Push---';
        $device_token = "cnE3SO3e2Ps:APA91bFtwKeeIFo60uwgDWk_9VTAFxehCAGg3yglrO0zeeCEf8-QSRy2oi3qUiiE59NQ-0q_HuHWZs_gmFKTsdo40E1xQd0_Jtw4GTI0P68NpTfD3JJP9Sd4RNkYqttDp2A8eVDUSEou";
        $title = 'Message Received!';
        $msg = 'Hello World';
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
                'body' => ["msg" => $msg, "act" => 1, "key" => 12],
                'experienceId' => \Yii::$app->params['fcm']['experienceId'],
                'notificationId' => -1,
                'isMultiple' => false,
                'remote' => true,
                'notification_object' => ['title' => $title,
                    'experienceId' => \Yii::$app->params['fcm']['experienceId'],
                    'notificationId' => -1,
                    'isMultiple' => false,
                    'remote' => true,
                    "data" => ["msg" => $msg, "act" => 1, "key" => 12]
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
            echo '<br>Some thing went wrong..';
            $this->transaction->wrap(function () use ($device_token) {
                \common\models\userdevice\UserDevice::deleteAll('token="' . $device_token . '"');
            });
            exit();
        }
        echo '<br>Successfully send push to Android';
        exit();
    }

    public function actionIosPush() {
        echo '---In Ios Push---';
        $device_token = '2133d49c15f96ad4e5e861ca16591f513fac5d62ae8f5087a65bda5abf5e2b84';
        $ar_msg['dev_token'] = $device_token;
        $ar_msg['title'] = 'Test Title';
        $ar_msg['msg'] = 'testing msg..';
        $ar_msg['key'] = 27;
        $ar_msg['pk'] = 1;
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
            echo '<br> Some thing went wrong..';
            $this->transaction->wrap(function () use ($device_token) {
                \common\models\userdevice\UserDevice::deleteAll('token="' . $device_token . '"');
            });
            exit();
        }
        echo 'Successfully send push to Ios';
        exit();
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
        $dev_token = $ar_msg['dev_token'];  // Device token
        $url = sprintf("%s/%s", $endPoint, $dev_token);
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

    public function actionIndex() {
        $this->layout = 'home';
        // $o_user =User::find()->where(['id' => 1])->one();
        // $to_user =User::find()->where(['id' => 3])->one();
        // $pro = User::find()->where(['id' => 4])->one();
        // OrderPlace::instance()->from($o_user)->about($pro)->send($to_user);
        return $this->render('index');
    }

    public function actionSend() {
        $o_user = User::find()->where(['id' => 1])->one();
        $to_user = User::find()->where(['id' => 3])->one();
        $message = Message::find()->where(['id' => 4])->one();
        MessageNotification::instance()->from($o_user)->about($message)->send($to_user);
    }

    public function actionCheck() {
//        MessageNotification
        $queueObject = unserialize('O:49:"common\modules\notification\jobs\SendNotification":2:{s:12:"notification";C:64:"common\modules\customnotification\components\MessageNotification":104:{a:3:{s:11:"sourceClass";s:26:"common\models\chat\Message";s:8:"sourcePk";i:29;s:13:"originator_id";i:3;}}s:11:"recipientId";i:79;}');
//        OrderPlace
//        $queueObject = unserialize('O:49:"common\modules\notification\jobs\SendNotification":2:{s:12:"notification";C:55:"common\modules\customnotification\components\OrderPlace":100:{a:3:{s:11:"sourceClass";s:23:"shop\entities\User\User";s:8:"sourcePk";i:4;s:13:"originator_id";i:1;}}s:11:"recipientId";i:3;}');
        $recipient = User::findOne(['id' => $queueObject->recipientId]);
        d("Jobs >> Send Notifications");
        if ($recipient !== null) {
            Yii::$app->notification->send($queueObject->notification, $recipient);
        }
    }

    public function actionSquareMobileForm() {
        $this->layout = 'blankpayment';
        $requestType = (isset(\Yii::$app->request->get()['request']) &&
                \Yii::$app->request->get()['request'] != null ) ? \Yii::$app->request->get()['request'] : '';
        $response = [];
        $response['request'] = $requestType;
        try {
            $token = explode(' ', \Yii::$app->request->headers->get('authorization'))[1];
        } catch (Exception $e) {
            throw new \yii\web\HttpException(404, $e->getMessage());
        }

        if ($token == null) {
            throw new \yii\web\HttpException(404, "Page not found.");
        }

        $userQuery = (new \yii\db\Query())
                ->select("*")
                ->from('oauth_access_tokens')
                ->where(['access_token' => $token])
                ->andWhere(['>', 'expires', date("Y-m-d H:i:s")])
                ->one();

        $user = User::find()->where(['id' => $userQuery['user_id']])->one();
        $cardArr = [];
        $activeCard = '';
        $cards = UsersSquareInfo::find()->where(['user_id' => $user->id])->all();
        if ($cards != null) {
            $activeCard = $user->getActiveCard();
            if ($user->square_cust_id != null) {
                $square = new SquarePaymentService;
                $result = $square->retrieveCustomer($user->square_cust_id);
                if (is_object($result)) {
                    $errors = $result->getErrors();

                    if ($errors == null) {
                        $customer = $result->getCustomer();
                        $cards = $customer->getCards();
                        $cardsArr = [];
                        $i = 0;
                        foreach ($cards as $card) {

                            $makeImageName = '';

                            if ($card->getCardBrand() == 'VISA') {
                                $makeImageName = 'visa.png';
                            } else if ($card->getCardBrand() == 'MASTERCARD') {
                                $makeImageName = 'master.png';
                            } else if ($card->getCardBrand() == 'DISCOVER') {
                                $makeImageName = 'discover.png';
                            } else if ($card->getCardBrand() == 'DISCOVER_DINERS') {
                                $makeImageName = 'diners_club.png';
                            } else if ($card->getCardBrand() == 'JCB') {
                                $makeImageName = 'jcb.png';
                            } else if ($card->getCardBrand() == 'AMERICAN_EXPRESS') {
                                $makeImageName = 'americanexp.png';
                            } else if ($card->getCardBrand() == 'CHINA_UNIONPAY') {
                                $makeImageName = 'cup.png';
                            } else {
                                $makeImageName = 'democard.png';
                            }

                            $cardsArr[$i] = [
                                'sourceId' => $card->getId(),
                                'cardBrand' => $card->getCardBrand(),
                                'last4Digit' => $card->getLast4(),
                                'expMonth' => $card->getExpMonth(),
                                'expYear' => $card->getExpYear(),
                                'imageLink' => \Yii::$app->params['staticHostInfo'] . '/' . 'cards' . '/' . $makeImageName,
                            ];
                            $i++;
                        }
                        $cardArr = $cardsArr;
                    }
                }
            }
        }
        return $this->render('form', ['cards' => $cardArr, 'response' => $response, 'activeCard' => $activeCard]);
    }

    public function actionAbout() {
        $this->layout = 'main';
        return $this->render('about');
    }

    public function actionTerms() {
        $this->layout = 'main';
        return $this->render('terms');
    }

    public function actionPolicy() {
        $this->layout = 'main';
        return $this->render('policy');
    }

    public function actionKnowledge() {
        $this->layout = 'main';
        return $this->render('knowledge');
    }

}
