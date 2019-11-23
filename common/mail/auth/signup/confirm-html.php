<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \shop\entities\User\User */
$confirmLink = Yii::$app->params['frontendHostInfo'].Yii::$app->urlManager->createUrl(['auth/signup/confirm', 'token' => $user->email_confirm_token]);
// $confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/signup/confirm', 'token' => $user->email_confirm_token]);
$hi = "Hi, ".$user->name.",";
$subject = "Confirm Your Email";
$message1 = "Follow the link below to confirm your email:";
$message2 = Html::a(Html::encode($confirmLink), $confirmLink);
?>
<table class="es-content" cellspacing="0" cellpadding="0" align="center">
    <tbody>
        <tr>
            <td class="esd-stripe" esd-custom-block-id="3109" align="center">
                <table class="es-content-body" style="background-color: rgb(255, 255, 255);" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center">
                    <tbody>
                        <tr>
                            <td class="esd-structure es-p20t es-p20b es-p40r es-p40l" esd-general-paddings-checked="true" align="left">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tbody>
                                        <tr>
                                            <td class="esd-container-frame" width="520" valign="top" align="center">
                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="esd-block-text" align="left" bgcolor="transparent">
                                                                <h1 style="color: #252440;"><?= $subject ?></h1>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="esd-block-spacer es-p5t es-p20b" align="left">
                                                                <table width="5%" height="100%" cellspacing="0" cellpadding="0" border="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <!--<td style="border-bottom: 2px solid rgb(153, 153, 153); background: rgba(0, 0, 0, 0) none repeat scroll 0% 0%; height: 1px; width: 100%; margin: 0px;"></td>-->
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="esd-block-text es-p10b" align="left">
                                                                <p><span style="font-size: 16px; line-height: 150%;"><?= $hi ?></span></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="esd-block-text" align="left">
                                                                <p><?= $message1?></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="esd-block-text" align="left" esd-links-color="#252440">
                                                                <p><?= $message2?></p>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="esd-structure es-p20t es-p20b es-p40r es-p40l" esd-general-paddings-checked="true" align="left">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tbody>
                                        <tr>
                                            <td class="esd-container-frame" width="520" valign="top" align="center">
                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="esd-block-spacer es-p20t es-p20b es-p5r" align="center">
                                                                <table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="border-bottom: 1px solid rgb(255, 255, 255); background: rgba(0, 0, 0, 0) none repeat scroll 0% 0%; height: 1px; width: 100%; margin: 0px;"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>