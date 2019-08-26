<?php
namespace shop\forms\auth;

use yii\base\Model;
use shop\entities\User\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $name;
    public $email;
    public $phone;
    public $password;
    public $rePassword;
    public $reCaptcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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

            ['rePassword', 'required'],
            ['rePassword', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match" ],
        
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => \Yii::$app->params['reCaptcha']['secret-key']]
        ];
    }
}
