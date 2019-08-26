<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use shop\entities\Shop\Product\Product;
use common\models\usertalent\UserTalent;
use common\services\UserAddressService;
use shop\entities\User\User;
use common\models\useraddress\UserAddress;

class UserController extends Controller {

    public function actionDashboard() {
        return $this->render('dashboard');
    }

    public function actionPortfolio($id) {
        $talent = UserTalent::findOne($id);
        $dataProvider = Product::find()->all();
        return $this->render('portfolio', ['products' => $dataProvider, 'talent' => $talent]);
    }

    public function actionProfile() {
        $this->layout = 'main';
        $model = User::findOne(Yii::$app->user->id);
        $talent = UserTalent::find()->where(['user_id' => Yii::$app->user->id])->one();
        $user_addresses = UserAddressService::userAddress('get', Yii::$app->user->id);
        return $this->render('my_profile', [
                    'model' => $model,
                    'talent' => $talent,
                    'user_addresses' => $user_addresses,
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

}
