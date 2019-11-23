<?php

/* @var $this yii\web\View */
/* @var $user \shop\entities\User\User */
$hi = "Hi, ".$model->user->name.",";
$message1 = "You have successfully received <strong>".$model->amount."$</strong> for ".str_replace("_", " ", $model->type).".";
$message2 = "By this referral code: <strong>".$model->referral_code."$</strong>.";
?>
<?= $subject ?>
<?= $hi ?>
<?= $message1 ?>
<?= isset($message3) ? $message3 : ""; ?>