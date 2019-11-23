<?php

namespace shop\forms\auth;

use yii\base\Model;

class LoginForm extends Model {

    public $username;
    public $password;
    public $rememberMe = true;
    public $reCaptcha;

    public function rules() {
        $rules = [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
        ];
        if (\Yii::$app->params['production']) {
            array_push($rules, [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => \Yii::$app->params['reCaptcha']['secret-key']]);
        }
        return $rules;
    }

}
