<?php

/* @var $this yii\web\View */
/* @var $user \shop\entities\User\User */

// $confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/signup/confirm', 'token' => $user->email_confirm_token]);
$confirmLink = Yii::$app->params['frontendHostInfo'].Yii::$app->urlManager->createUrl(['auth/signup/confirm', 'token' => $user->email_confirm_token]);
?>
Hello <?= $user->name ?>,

Follow the link below to confirm your email:

<?= $confirmLink ?>
