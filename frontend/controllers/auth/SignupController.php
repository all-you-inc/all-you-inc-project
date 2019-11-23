<?php

namespace frontend\controllers\auth;

use shop\useCases\auth\SignupService;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use shop\forms\auth\SignupForm;
use common\services\IndustryService;
use common\services\TalentMasterService;
use common\services\DjGenreService;
use common\services\InstrumentService;
use common\services\InstrumentSpecificationService;
use common\services\MusicGenreService;
use common\models\usertalent\UserTalent;
use shop\entities\User\User;
use common\models\membership\Membership;
use common\models\usersubscription\UserSubscription;
use common\models\useraddress\UserAddress;
use common\services\UserAddressService;
use common\services\UserPaymentService;
use common\services\UserReferralService;
use common\services\UserMlmService;
use common\models\userreferral\UserReferral;
use shop\services\TransactionManager;
use shop\repositories\UserRepository;
use common\models\userfunds\UserFunds;
use yii\web\NotFoundHttpException;
use common\services\SquarePaymentService;

class SignupController extends Controller {

    private $transaction;
    public $layout = 'cabinet';
    private $service;
    private $users;

    public function __construct($id, $module, SignupService $service, $config = [], UserRepository $users, TransactionManager $transaction) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->transaction = $transaction;
        $this->users = $users;
        }

        public function behaviors(): array {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    private function actionPlancolors() {
        $blue['code'] = '#005c99';
        $blue['class'] = 'blue';
        $green['code'] = '#008000';
        $green['class'] = 'green';
        $red['code'] = '#db2c29';
        $red['class'] = 'red';
        $color[0] = $blue;
        $color[1] = $green;
        $color[2] = $red;
        return $color;
    }

    public function actionCheckmlm() {
//        $transaction_id = UserMlmService::createSignupMlm('7Y7Nr0333VqMsDOmJfOBV5Hqr6Ql1a6a', '3.99', 69);
        $transaction_id = UserMlmService::createSignupMlm('7Y7Nr0333VqMsDOmJfOBV5Hqr6Ql1a6a', '3.99', 69);
        dd($transaction_id);
        if ($transaction_id) {
            $funds = UserFunds::find()->where(['transaction_id' => $transaction_id])->all();
            if ($funds) {
                foreach ($funds as $fund) {
                    $this->users->sendEmail($fund, 'funds/user/fund-html', 'funds/user/fund-text', $fund->user->email, 'Your AllYouInc Wallet Transection');
                }
            }
        }
    }

    public function actionRequest($id = '', $referral = '') {
        $this->layout = 'main';
        $form = new SignupForm;

        if (Yii::$app->request->post()) {
            $form = $this->service->setForm(Yii::$app->request->post());
            if ($form->validate()) {
                try {
                    if (isset($id) && $id != NULL) {
                        $form['memberShip'] = $id;
                    }
                    $this->service->signup($form);
                    if (isset($referral) && $referral != NULL) {
//                    create user tiers without limitations
                        UserReferralService::createReferrals($referral, $form->email);
                    }
                    Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                    return $this->goHome();
                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
        }
        return $this->render('request', [
                    'model' => $form,
        ]);
    }

    private function actionPayment($membership_id) {
        $this->layout = 'main';
        $membership = Membership::findOne($membership_id);
        $params = [];
        $params['note'] = 'Purchase MemberShip';
        $subscriptionChecker = false;
        if ($membership_id != Membership::FreeTalent && $membership_id != Membership::FreeTalentWithProduct && $membership_id != Membership::CustomerId && $membership_id != Membership::FanId) {
            if ($membership_id != Membership::Promoter) {
                $subscriptions = UserSubscription::find()->where(['user_id' => \Yii::$app->user->id, 'type' => 'membership'])->all();
                if ($subscriptions != null) {
                    foreach ($subscriptions as $sub) {
                        if ($sub->ref_id == Membership::Talent || $sub->ref_id == Membership::TalentWithProduct) {
                            $paymentGateway = UserPaymentService::paymentGateway($membership->price, $params);
                            $subscriptionChecker = true;
                            break;
                        }
                    }
                    if (!$subscriptionChecker) {
                        $paymentGateway = [];
                        $paymentGateway['code'] = 200;
                    }
                } else {
                    $paymentGateway = [];
                    $paymentGateway['code'] = 200;
                }
            } else {
                $paymentGateway = UserPaymentService::paymentGateway($membership->price, $params);
                $subscriptionChecker = true;
            }
        } else {
            $paymentGateway = [];
            $paymentGateway['code'] = 200;
        }
        if ($paymentGateway['code'] == 200) {
            $user = \Yii::$app->user->identity->getUser();
            $user_subscription = UserPaymentService::createSubscription('membership', $membership_id, $user->id);
            if ($user_subscription && $membership_id != Membership::FreeTalent && $membership_id != Membership::FreeTalentWithProduct && $membership_id != Membership::CustomerId && $membership_id != Membership::FanId && $subscriptionChecker) {
                $params = [];
                $params['user_id'] = $user->id;
                $params['amount'] = $membership->price;
                $params['currency_id'] = $membership->currency_id;
                $params['type'] = 'subscription';
                $params['ref_id'] = $user_subscription->id;
                if (isset($paymentGateway['transection_id'])) {
                    $params['transection_id'] = $paymentGateway['transection_id'];
                    $params['status'] = 1;
                }
                $payment = UserPaymentService::createPayment($params);
                if ($payment) {
                    $user_referral = UserReferral::find()->where(['user_id' => $user->id])->one();
                    $this->transaction->wrap(function () use ($user_referral, $membership, $user) {
                        $ref_code = '';
                        if (isset($user_referral->referral_code) && $user_referral->referral_code != '') {
                            $ref_code = $user_referral->referral_code;
                        }
                        $transaction_id = UserMlmService::createSignupMlm($ref_code, $membership->price, $user->id);
                        if ($transaction_id) {
                            $funds = UserFunds::find()->where(['transaction_id' => $transaction_id])->all();
                            if ($funds) {
                                foreach ($funds as $fund) {
                                    $this->users->sendEmail($fund, 'funds/user/fund-html', 'funds/user/fund-text', $fund->user->email, 'Your AllYouInc Wallet Transaction For User Signup');
                                }
                            }
                        }
                    });
                }
                return TRUE;
            } else {
                return TRUE;
            }
        }
        if (isset($paymentGateway['message'])) {
            Yii::$app->session->setFlash('error', $paymentGateway['message']);
        }

        return FALSE;
    }

// $token = User Email token
    /**
     * @param $token
     * @return mixed
     */
    public function actionPlan() {
        $this->layout = 'main';
        $plans = Membership::find()->where(['status' => 'active', 'is_deleted' => 0])->limit(3)->orderBy('sort ASC')->all();
        $color = $this->actionPlancolors();
        if (isset(Yii::$app->request->post()['plan_id'])) {
            $membership = Yii::$app->request->post()['plan_id'];
            if (isset($_GET['referral']) && $_GET['referral'] != NULL) {
                return $this->redirect(['request', 'id' => $membership, 'referral' => $_GET['referral']]);
            } else {
                return $this->redirect(['request', 'id' => $membership]);
            }
        }
        return $this->render('plan', [
                    'plans' => $plans,
                    'color' => $color,
        ]);
    }

// $token = User Email token
    /**
     * @param $token
     * @return mixed
     */
    public function actionSelectplan() {
        $this->layout = 'main';
        $session = \Yii::$app->session;
        $user = \Yii::$app->user->identity->getUser();
        $plans = Membership::find()->where(['group_id' => $user->membership_id])->orderBy('sort DESC')->all();
        $index = $user->membership_id - 1;
        $color = $this->actionPlancolors($index);
        if (Yii::$app->request->post()) {
//            dd(Yii::$app->request->post());
            $membership_id = Yii::$app->request->post()['plan_id'];
            $user = \Yii::$app->user->identity->getUser();

            if (isset(\Yii::$app->request->post()['card_given']) && \Yii::$app->request->post()['card_given'] == 1) {
                SquarePaymentService::$checkoutCard = 1;
            }
            $membership = Membership::findOne($membership_id);
            if ($membership == NULL) {
                throw new NotFoundHttpException('The membership does not exist.');
            }

            if ($user->isUserCardNeedToAdd($membership_id)) {
                if (SquarePaymentService::$checkoutCard == 0) {
                    $session['membership_id'] = $membership_id;
                    return $this->redirect(\yii\helpers\Url::base('https') . '/' . 'square/details?request=membership');
                }
            }


            $payment = $this->actionPayment($membership_id);
            if ($payment) {
                $user->membership_id = NULL;
                $user->update();
                if (isset($session['membership_id'])) {
                    $session->remove('membership_id');
                }
                SquarePaymentService::$checkoutCard == 0;
                Yii::$app->session->setFlash('success', 'Successfully selected a membership plan.');
                if ($membership_id == Membership::TalentWithProduct || $membership_id == Membership::Talent) {
                    return $this->redirect(['user/addtalent']);
                }
                return $this->goHome();
            }
        }
        return $this->render('selectplan', [
                    'plans' => $plans,
                    'color' => $color[$index],
                    'membership_id' => $user->membership_id,
        ]);
    }

// $token = User Email token
    /**
     * @param $token
     * @return mixed
     */
    public function actionConfirm($token) {
        try {
            $user = User::find()->where(['email_confirm_token' => $token])->one();
            $this->service->confirm($token);
            Yii::$app->session->setFlash('success', 'Your email is confirmed successfully.');
            return $this->redirect(['auth/auth/login']);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->goHome();
    }

    /**
     * @param $token
     * @return mixed
     */
    public function actionProfile($auth_key) {
        $this->layout = 'main';
        $user = User::find()->where(['auth_key' => $auth_key])->one();
        $id = $user->id;
        $model = UserTalent::find()->where(['user_id' => $id])->one();
        if (!$model) {
            $model = new UserTalent;
        }
        $industries = IndustryService::getAll();
        if (Yii::$app->request->post()) {
            $form_data = Yii::$app->request->post();
            $model->attributes = $form_data;
            $model->user_id = $id;
            $model->created_at = time();
            $model->created_by = $id;
            $model->modified_at = time();
            $model->modified_by = $id;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Profile update successfully');
                return $this->goHome();
            }
        }
        return $this->render('profile', [
                    'industries' => $industries
        ]);
    }

    public function actionGettalent() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $insdustry_id = isset(Yii::$app->request->post()['id']) ? Yii::$app->request->post()['id'] : '-';
        if ($insdustry_id != null || $insdustry_id != '') {
            $talents = TalentMasterService::getTalentMasterRecordByIndustryId($insdustry_id);
            $count = 0;
            $dd = '<select class="form-control" name="talent_id" onchange="talent(this.value)" id="selected-talent">';
            $dd .= '<option value="">Please Select Any Talent</option>';
            if (count($talents) > 0) {
                foreach ($talents as $talent) {
                    $dd .= '<option value="' . $talent->talentMaster->id . '">' . $talent->talentMaster->name . '</option>';
                }
            }
            $dd .= '</select>';
            return $dd;
        } else {
            return null;
        }
    }

    public function actionGetdjgenre() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $djgenres = DjGenreService::getAll();
        $count = 0;
        $dd = '<select class="form-control" name="dj_genre_id" id="selected-dj_genre">';
        $dd .= '<option value="">Please Select Any Dj Genre</option>';
        if (count($djgenres) > 0) {
            foreach ($djgenres as $djgenre) {
                $dd .= '<option value="' . $djgenre->id . '">' . $djgenre->name . '</option>';
            }
        }
        $dd .= '</select>';
        return $dd;
    }

    public function actionGetmusic_genre() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $music_genres = MusicGenreService::getAll();
        $count = 0;
        $dd = '<select class="form-control" name="music_genre_id" id="selected-music_genre">';
        $dd .= '<option value="">Please Select Any Music Genre</option>';
        if (count($music_genres) > 0) {
            foreach ($music_genres as $music_genre) {
                $dd .= '<option value="' . $music_genre->id . '">' . $music_genre->name . '</option>';
            }
        }
        $dd .= '</select>';
        return $dd;
    }

    public function actionGetinstrument() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $instruments = InstrumentService::getAll();
        $count = 0;
        $dd = '<select class="form-control" name="instrument_id" onchange="getinstrumentspec(this.value)" id="selected-instrument">';
        $dd .= '<option value="">Please Select Any Instrument</option>';
        if (count($instruments) > 0) {
            foreach ($instruments as $instrument) {
                $dd .= '<option value="' . $instrument->id . '">' . $instrument->name . '</option>';
            }
        }
        $dd .= '</select>';
        return $dd;
    }

    public function actionGetinstrumentspec() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $instrument_id = isset(Yii::$app->request->post()['id']) ? Yii::$app->request->post()['id'] : '-';
        if ($instrument_id != null || $instrument_id != '-') {
            $instrument_specs = InstrumentSpecificationService::getInstrumentSpecificationRecordByInstrumentId($instrument_id);
            $count = 0;
            $dd = '<select class="form-control" name="instrument_spec_id" id="selected-instrument_spec">';
            $dd .= '<option value="">Please Select Any Specification</option>';
            if (count($instrument_specs) > 0) {
                foreach ($instrument_specs as $spec) {
                    $dd .= '<option value="' . $spec->id . '">' . $spec->name . '</option>';
                }
            }
            $dd .= '</select>';
            return $dd;
        }
    }

    public function actionGetgenderstatus() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $insdustry_id = isset(Yii::$app->request->post()['id']) ? Yii::$app->request->post()['id'] : '-';
        $gender_status = IndustryService::getIndusteryGenderFieldStatus($insdustry_id);
        return $gender_status;
    }

    public function actionGetmusicgenderstatus() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $talent_id = isset(Yii::$app->request->post()['id']) ? Yii::$app->request->post()['id'] : '-';
        $gender_status = IndustryService::getMusicTalentGenderFieldStatus($talent_id);
        return $gender_status;
    }

}
