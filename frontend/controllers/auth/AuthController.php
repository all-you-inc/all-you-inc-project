<?php

namespace frontend\controllers\auth;

use common\auth\Identity;
use shop\useCases\auth\AuthService;
use Yii;
use yii\web\Controller;
use yii\web\User;
use shop\forms\auth\LoginForm;
use common\models\usersubscription\UserSubscription;
use common\models\usertalent\UserTalent;
use common\services\UserReferralService;

class AuthController extends Controller {

    public $layout = 'cabinet';
    private $service;

    public function __construct($id, $module, AuthService $service, $config = []) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function actionLogin() {
        $this->layout = 'main';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $form = new LoginForm();
//        if ($form->load(Yii::$app->request->post())) {
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->service->auth($form);
                Yii::$app->user->login(new Identity($user), $form->rememberMe ? Yii::$app->params['user.rememberMeDuration'] : 0);
                UserReferralService::checkAndCreatePromoFromSession();
                $this->actionUsersubscription($user);
                return $this->goBack();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('login', [
                    'model' => $form,
        ]);
    }

    private function actionTalentprofile($user) {
//        dd($user->canShowTalent());
//        if (isset($user->getSubscription('membership')[0]->ref_id) || ($user->getSubscription('membership')[0]->ref_id == 1 || $user->getSubscription('membership')[0]->ref_id == 2)) { //for Talent type membership
        if ($user->canShowBothTalent()) { //for Talent type membership
            $check = UserTalent::find()->where(['user_id' => $user->id])->one();
            if (!$check instanceof UserTalent) {
                Yii::$app->session->setFlash('success', 'Successfully login, please update your talent profile.');
                Yii::$app->user->setReturnUrl(['addtalent']);
            } else {
                Yii::$app->session->setFlash('success', 'Successfully login.');
            }
        }
    }

    private function actionUsersubscription($user) {
        if (isset($user->membership_id) && $user->membership_id != '' && $user->membership_id != NULL) {
            Yii::$app->session->setFlash('success', 'Successfully login, please select any membership plan');
            Yii::$app->user->setReturnUrl(['selectplan']);
        } else {
            Yii::$app->session->setFlash('success', 'Successfully login.');
            if ($user->canShowBothTalent() || $user->isPromoter()) {
                Yii::$app->user->setReturnUrl(['dashboard']);
            }
        }
    }

    /**
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
