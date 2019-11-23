<?php

namespace shop\repositories;

use shop\dispatchers\EventDispatcher;
use shop\entities\User\User;
use yii\mail\MailerInterface;

class UserRepository
{
    private $dispatcher;
    private $mailer;

    public function __construct(EventDispatcher $dispatcher,MailerInterface $mailer)
    {
        $this->dispatcher = $dispatcher;
        $this->mailer = $mailer;
    }

    public function findByUsernameOrEmail($value): ?User
    {
        return User::find()->andWhere(['or', ['username' => $value], ['email' => $value]])->one();
    }

    public function findByNetworkIdentity($network, $identity): ?User
    {
        return User::find()->joinWith('networks n')->andWhere(['n.network' => $network, 'n.identity' => $identity])->one();
    }

    public function get($id): User
    {
        return $this->getBy(['id' => $id]);
    }

    public function getByEmailConfirmToken($token): User
    {
        return $this->getBy(['email_confirm_token' => $token]);
    }

    public function getByEmail($email): User
    {
        return $this->getBy(['email' => $email]);
    }

    public function getByPasswordResetToken($token): User
    {
        return $this->getBy(['password_reset_token' => $token]);
    }

    public function existsByPasswordResetToken(string $token): bool
    {
        return (bool) User::findByPasswordResetToken($token);
    }

    /**
     * @param $productId
     * @return iterable|User[]
     */
    public function getAllByProductInWishList($productId): iterable
    {
        return User::find()
            ->alias('u')
            ->joinWith('wishlistItems w', false, 'INNER JOIN')
            ->andWhere(['w.product_id' => $productId])
            ->each();
    }

    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }else{

            $sent = $this->mailer
            ->compose(
                ['html' => 'auth/signup/confirm-html', 'text' => 'auth/signup/confirm-text'],
                ['user' => $user]
            )
            ->setTo($user->email)
            ->setSubject('Signup confirm')
            ->send();

        }
        $this->dispatcher->dispatchAll($user->releaseEvents());
    }

    public function sendEmail($model,$html, $text, $email, $subject): void
    {
        d($model);
        d($html);
        d($text);
        d($email);
        d($subject);
            $sent = $this->mailer
            ->compose(
                ['html' => $html, 'text' => $text],
                ['model' => $model, 'subject' => $subject]
            )
            ->setTo($email)
            ->setSubject($subject)
            ->send();
    }

    public function remove(User $user): void
    {
        if (!$user->delete()) {
            throw new \RuntimeException('Removing error.');
        }
        $this->dispatcher->dispatchAll($user->releaseEvents());
    }

    private function getBy(array $condition): User
    {
        if (!$user = User::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('User not found.');
        }
        return $user;
    }
}