<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use shop\entities\Shop\Product\Product;
use common\models\usertalent\UserTalent;
use common\services\UserAddressService;
use shop\entities\User\User;
use common\models\useraddress\UserAddress;
use shop\forms\manage\Shop\Product\PhotosForm;
use common\models\userprofileimage\UserProfileImage;
use yii\helpers\BaseFileHelper;
use common\services\IndustryService;
use common\services\UserAccessService;
use common\services\UserPaymentService;
use common\models\membership\MsItems;
use common\models\usersubscription\UserSubscription;
use common\models\membership\Membership;

class UserController extends Controller {

    public function actionDashboard() {
        return $this->render('dashboard');
    }

    public function actionPortfolio($id) {
        $talent = UserTalent::findOne($id);
        $dataProvider = Product::find()->all();
        return $this->render('portfolio', ['products' => $dataProvider, 'talent' => $talent]);
    }

    public function actionSubscription() {
        $this->layout = 'main';
        $user = \Yii::$app->user->identity->getUser();
        $membership_id = $user->getMembershipId();
        if ($membership_id == Membership::FreeTalent) {
            $membership_id = Membership::Talent;
        } elseif ($membership_id == Membership::FreeTalentWithProduct) {
            $membership_id = Membership::TalentWithProduct;
        }
//        d($membership_id);
        $basic = [];
        if ($user->getMembershipId() != Membership::Talent && $user->getMembershipId() != Membership::TalentWithProduct) {
            $basic = UserPaymentService::getAllSubscriptions($membership_id, 'basic');
        }
//        dd($basic);
        $addons = UserPaymentService::getAllSubscriptions($membership_id, 'addons');
        $membership = UserPaymentService::getAllSubscriptions($membership_id, 'membership');
        if ($_POST) {
            $price = 0;
            $group_id = abs(crc32(uniqid()));
            if (isset($_POST['basic']) && $_POST['basic'] != NULL) {
                UserSubscription::updateAll(['status' => 'in-active'], ['user_id' => $user->id, 'type' => 'membership']);
                $item = MsItems::findOne($_POST['basic']);
                $price += $item->price;
                UserPaymentService::createSubscription('membership', $item->membership_id, $user->id, $group_id);
            }
            if (isset($_POST['addons'])) {
                foreach ($_POST['addons'] as $addon) {
                    UserSubscription::updateAll(['status' => 'in-active'], ['id' => $addon]);
                    $item = MsItems::findOne($addon);
                    $price += $item->price;
                    UserPaymentService::createSubscription('addons', $item->id, $user->id, $group_id);
                }
            }
            Yii::$app->session->setFlash('success', 'Subscription add successfully');
            return $this->redirect(['/profile']);
        }
        return $this->render('subscription', [
                    'basic' => $basic,
                    'addons' => $addons,
//                    'membership' => $membership,
        ]);
    }

    public function actionAddtalent() {
        $this->layout = 'main';
        $access = UserAccessService::checkByKey(\Yii::$app->user->identity->getUser(), 'TALENT');
        if ($access) {
            $limit = UserAccessService::checkLimitByMsItem(\Yii::$app->user->identity->getUser(), $access, 'TALENT');
            if ($limit) {
                $id = Yii::$app->user->id;
                if (Yii::$app->request->post()) {
                    $form_data = Yii::$app->request->post();
                    $model = new UserTalent;
                    $model->attributes = $form_data;
                    if (isset($form_data['group_gender']) && $form_data['group_gender'] != '') {
                        $model->gender = $form_data['group_gender'];
                    }
                    $model->user_id = $id;
                    $model->created_at = time();
                    $model->created_by = $id;
                    $model->modified_at = time();
                    $model->modified_by = $id;
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Add Talent successfully');
                        return $this->redirect(['/profile']);
                    }
                }
                return $this->render('add_talent', [
                            'industries' => IndustryService::getAll()
                ]);
            } else {
                Yii::$app->session->setFlash('error', 'Limit end in your current plan, Please subscribe Addons for more.');
                return $this->redirect(['/profile']);
            }
        } else {
            Yii::$app->session->setFlash('error', 'Access denied in your current plan, Please update your membership plan.<br><a href="' . Html::encode(Url::to(['/subscription'])) . '">Click Here</a>');
            return $this->redirect(['/profile']);
        }
    }

    public function actionProfile() {
        $this->layout = 'main';
        $model = User::findOne(Yii::$app->user->id);
        $talents = UserTalent::find()->where(['user_id' => Yii::$app->user->id])->all();
        $user_addresses = UserAddressService::userAddress('get', Yii::$app->user->id);
        $subscriptions = UserPaymentService::getAllSubscriptions($model->getMembershipId());
        return $this->render('my_profile', [
                    'model' => $model,
                    'talents' => $talents,
                    'user_addresses' => $user_addresses,
                    'subscriptions' => $subscriptions,
        ]);
    }

    public function actionAddaddress() {
        $this->layout = 'main';
        $model = new UserAddress;
        $countries = UserAddressService::getCountries();
        if (Yii::$app->request->post()) {
            $result = UserAddressService::userAddress('post', Yii::$app->user->id, null, $model, Yii::$app->request->post());
            if ($result) {
                Yii::$app->session->setFlash('success', 'Address Added successfully');
                return $this->redirect(['profile']);
            }
        }
        return $this->render('add_address', array('model' => $model, 'countries' => $countries));
    }

    public function actionUpdateaddress($id) {
        $this->layout = 'main';
        $model = UserAddressService::userAddress('get', null, $id);
        $countries = UserAddressService::getCountries();
        if (Yii::$app->request->post()) {
            $result = UserAddressService::userAddress('put', Yii::$app->user->id, null, $model, Yii::$app->request->post());
            if ($result) {
                Yii::$app->session->setFlash('success', 'Address Updated successfully');
                return $this->redirect(['profile']);
            }
        }
        return $this->render('update_address', array('model' => $model, 'countries' => $countries));
    }

    public function actionUpdateprofile() {
        $this->layout = 'main';
        $model = User::findOne(Yii::$app->user->id);
        if (Yii::$app->request->post()) {
            $form_data = Yii::$app->request->post();
            $model->name = $form_data['name'];
            $model->phone = $form_data['phone'];
            $model->city = $form_data['city'];
            $model->state = $form_data['state'];
            $model->country = $form_data['country'];
            $model->updated_at = time();
            if ($model->update()) {
                Yii::$app->session->setFlash('success', 'Profile update successfully');
                return $this->redirect(['profile']);
            }
        }
        return $this->render('update_profile', [
                    'model' => $model
        ]);
    }

    public function actionUploadprofile() {
        $model = new PhotosForm;
        if (\Yii::$app->request->post()) {
            if ($model->validate()) {
                $path = UserProfileImage::getfullPath(UserProfileImage::$show_on_profile);
                $name = \Yii::$app->security->generateRandomString();
                if (is_dir($path)) {
                    UserProfileImage::deletePreviousFile($path);
                }
                if (!is_dir($path)) {
                    BaseFileHelper::createDirectory($path);
                }
                if ($model->files[0]->saveAs($path . $name . '.' . $model->files[0]->extension)) {
                    $image = UserProfileImage::createImage($name, $model->files[0]->extension, UserProfileImage::$show_on_profile);
                    if ($image->save()) {
                        return $this->redirect(\Yii::$app->request->referrer);
                    }
                    dd('save error');
                }
                dd('file saveAs error');
            }
            dd('validate error');
        }
        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionUploadbanner() {
        $model = new PhotosForm;
        if (\Yii::$app->request->post()) {
            if ($model->validate()) {
                $path = UserProfileImage::getfullPath(UserProfileImage::$show_on_banner);
                $name = \Yii::$app->security->generateRandomString();
                if (is_dir($path)) {
                    UserProfileImage::deletePreviousFile($path);
                }
                if (!is_dir($path)) {
                    BaseFileHelper::createDirectory($path);
                }
                if ($model->files[0]->saveAs($path . $name . '.' . $model->files[0]->extension)) {
                    $image = UserProfileImage::createImage($name, $model->files[0]->extension, UserProfileImage::$show_on_banner);
                    if ($image->save()) {
                        return $this->redirect(\Yii::$app->request->referrer);
                    }
                    Yii::$app->session->setFlash('error', 'Image Not Save In Model');
                }
                Yii::$app->session->setFlash('error', 'Image Not Save In Server');
            }
            Yii::$app->session->setFlash('error', 'File is Invalid');
        }

        return $this->redirect(\Yii::$app->request->referrer);
    }

}
