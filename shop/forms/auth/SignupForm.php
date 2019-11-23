<?php

namespace shop\forms\auth;

use yii\base\Model;
use shop\entities\User\User;

/**
 * Signup form
 */
class SignupForm extends Model {

    public $username;
    public $name;
    public $email;
    public $phone;
    public $password;
    public $rePassword;
    public $reCaptcha;
    public $memberShip;
    public $tiers_payout_id;

    /**
     * @inheritdoc
     */
    public function rules() {
        $rules = [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'email'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'This email_In_username has already been taken.'],
            ['username', 'string', 'max' => 255],
            ['name', 'string'],
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'max' => 255],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['phone', 'integer'],
            [['name', 'email', 'password', 'username'], 'required', 'on' => 'frontend'],
        ];
        if (\Yii::$app->params['production']) {
            array_push($rules, ['rePassword', 'required'],
                ['rePassword', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match"],
                [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => \Yii::$app->params['reCaptcha']['secret-key']]);
        }
        return $rules;
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['api'] = ['name', 'email', 'password', 'username']; //Scenario Values Only Accepted
        return $scenarios;
    }

}
