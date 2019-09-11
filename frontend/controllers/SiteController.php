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
/**
 * Site controller
 */
class SiteController extends Controller {

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
    public function actionIndex() {
        $this->layout = 'home';
        return $this->render('index');
    }

    public function actionSquareMobileForm()
    {
        $this->layout = 'blankpayment';
        
        try{
            $token = explode(' ',\Yii::$app->request->headers->get('authorization'))[1];
        }catch(Exception $e){
            throw new \yii\web\HttpException(404,$e->getMessage());
        }

        if($token == null){
            throw new \yii\web\HttpException(404,"Wrong Path");
        }

        $userQuery = (new \yii\db\Query())
        ->select("*")
        ->from('oauth_access_tokens')
        ->where(['access_token' => $token])
        ->andWhere(['>', 'expires' , date("Y-m-d H:i:s")])
        ->one();

        $user = User::find()->where(['id' => $userQuery['user_id']])->one();
        $cardArr = []; 

        $cards = UsersSquareInfo::find()->where(['user_id' => $user->id])->all();
        if($cards != null)
        {
            if($user->square_cust_id != null){
                $square = new SquarePaymentService;
                $result = $square->retrieveCustomer($user->square_cust_id);
                if(is_object($result))
                {
                    $errors = $result->getErrors();

                    if($errors == null)
                    {
                        $customer = $result->getCustomer();
                        $cards = $customer->getCards();
                        $cardsArr = [];
                        $i=0;
                        foreach($cards as $card){
                            $cardsArr[$i] = [
                                'sourceId' => $card->getId(),
                                'cardBrand' => $card->getCardBrand(),
                                'last4Digit' => $card->getLast4(),
                                'expMonth' => $card->getExpMonth(),
                                'expYear' => $card->getExpYear(),
                            ];
                            $i++;
                        }
                        $cardArr = $cardsArr;
                    }
                }
            }
        }
        return $this->render('form' , ['cards' => $cardArr]);
    }
}
