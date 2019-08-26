<?php

namespace shop\useCases\auth;

use shop\access\Rbac;
use shop\dispatchers\EventDispatcher;
use shop\entities\User\User;
use shop\forms\auth\SignupForm;
use shop\repositories\UserRepository;
use shop\services\RoleManager;
use shop\services\TransactionManager;

class SignupService
{
    private $users;
    private $roles;
    private $transaction;

    public function __construct(
        UserRepository $users,
        RoleManager $roles,
        TransactionManager $transaction
    )
    {
        $this->users = $users;
        $this->roles = $roles;
        $this->transaction = $transaction;
    }

    public function signup(SignupForm $form): void
    {
        $user = User::requestSignup(
            $form->username,
            $form->email,
            $form->name,
            $form->password
        );
        $this->transaction->wrap(function () use ($user) {
            $this->users->save($user);
            $this->roles->assign($user->id, Rbac::ROLE_USER);
        });
    }

    public function unsetFormPwd($form)
    {
        $form->password = null;
        return $form;
    }


    public function confirm($token): void
    {
        if (empty($token)) {
            throw new \DomainException('Empty confirm token.');
        }
        $user = $this->users->getByEmailConfirmToken($token);
        $user->confirmSignup();
        $this->users->save($user);
    }

    public function setForm($getPostData){
        $form = new SignupForm();
        $form->username = $getPostData['SignupForm']['email'];
        $form->email = $getPostData['SignupForm']['email'];
        $form->name = $getPostData['SignupForm']['name'];
        $form->password = $getPostData['SignupForm']['password'];
        $form->rePassword = $getPostData['SignupForm']['rePassword'];
        $form->reCaptcha = $getPostData['SignupForm']['reCaptcha'];
        return $form;
    }
}