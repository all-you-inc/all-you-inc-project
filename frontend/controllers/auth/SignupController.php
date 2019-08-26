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
use common\models\usermembership\UserMembership;
use common\models\useraddress\UserAddress;
use common\services\UserAddressService;

class SignupController extends Controller {

    public $layout = 'cabinet';
    private $service;

    public function __construct($id, $module, SignupService $service, $config = []) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors(): array
    {
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

private function actionCreateplan($plan_id, $email) {
    $user = User::find()->where(['email' => $email])->one();
    $plan = Membership::findOne($plan_id);
    $time = time();
    $model = new UserMembership;
    $model->user_id = $user->id;
    $model->membership_id = $plan_id;
    $model->status = 'active';
    $model->category = $plan->category;
    $model->created_at = $time;
    $model->created_by = $user->id;
    $model->modified_at = $time;
    $model->modified_by = $user->id;
    if ($model->save()) {
        return TRUE;
    }
}

public function actionRequest($plan_id) {
    $this->layout = 'main';
    $form = new SignupForm;
    if (Yii::$app->request->post()) {
        $form = $this->service->setForm(Yii::$app->request->post());
        if ($form->validate()) {
            try {
                $this->service->signup($form);
                $this->actionCreateplan($plan_id, $form->email);
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

// $token = User Email token
/**
 * @param $token
 * @return mixed
 */
public function actionPlan() {
    $this->layout = 'main';
    $plans = Membership::find()->where(['status' => 'active', 'is_deleted' => 0])->limit(3)->orderBy('level ASC')->all();
    $color = $this->actionPlancolors();
    if (Yii::$app->request->post()) {
        return $this->redirect(['auth/signup/request', 'plan_id' => Yii::$app->request->post()['plan_id']]);
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
