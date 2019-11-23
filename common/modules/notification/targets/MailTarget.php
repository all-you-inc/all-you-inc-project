<?php

namespace common\modules\notification\targets;

use Yii;
use yii\helpers\Html;
use common\modules\notification\components\BaseNotification;
use shop\entities\User\User;
use common\modules\notification\components\MailManager;
use shop\repositories\UserRepository;

/**
 *
 * @author buddha
 */
class MailTarget extends BaseTarget {

    /**
     * @inheritdoc
     */
    public $id = 'email';

    /**
     * Enable this target by default.
     * @var boolean
     */
    public $defaultSetting = true;

    /**
     * @var array Notification mail layout.
     */
    public $view = [
        'html' => '@notification/views/mails/wrapper',
        'text' => '@notification/views/mails/plaintext/wrapper'
    ];

    /**
     * @inheritdoc
     */
    public function getTitle() {
        return 'E-Mail';
        // return Yii::t('NotificationModule.targets', 'E-Mail');
    }

    /**
     * @inheritdoc
     */
    public function handle(BaseNotification $notification, User $user) {

        d('In Email Target');
//        d('$msg ==> ' . $msg);
        // Yii::$app->i18n->setUserLocale($recipient);
        Yii::$app->view->params['showUnsubscribe'] = true;
        Yii::$app->view->params['unsubscribeUrl'] = \yii\helpers\Url::to(['/notification/user'], true);

        // Note: the renderer is configured in common.php by default its an instance of MailTarget
        $renderer = $this->getRenderer();
        // d(Yii::$app->controller->renderFile('@common/modules/'.$notification->moduleId.'/views/'.$notification->viewName.'.php',['notification'=>$notification]));
        // d($this->render('common/modules/customnotification/views/'.$notification->viewName));
        // d(Yii::$app->controller->renderPartial('MobileTarget'));
//        $viewParams = \yii\helpers\ArrayHelper::merge([
//                    'headline' => '',
//                    'notification' => $notification,
//                    'space' => $notification->getSpace(),
//                    // 'content' => 'hello..',
//                    // 'content_plaintext' => 'hello..',
//                    'content' => Yii::$app->controller->renderPartial('@common/modules/' . $notification->moduleId . '/views/' . $notification->viewNameHtml, ['originator' => $notification->originator, 'user' => $user, 'source' => $notification->source]),
//                    'content_plaintext' => Yii::$app->controller->renderPartial('@common/modules/' . $notification->moduleId . '/views/' . $notification->viewNameText, ['originator' => $notification->originator, 'user' => $user, 'source' => $notification->source]),
//                        ], $notification->getViewParams());
        // $message = Yii::$app->mailer->compose($this->view, $viewParams);
        $msg = Yii::$app->controller->renderPartial('@common/modules/' . $notification->moduleId . '/views/' . $notification->viewNameHtml, ['originator' => $notification->originator, 'user' => $user, 'source' => $notification->source]);
        // $from = Yii::$app->settings->get('mailer.systemEmailAddress');
        $from = \Yii::$app->params['fromEmail'];
        $to = $user->email;
        $subject = trim($notification->getMailSubject());
        d('$to ==> ' . $to);
        d('$from ==> ' . $from);
        d('$subject ==> ' . $subject);
        d('$msg ==> ' . $msg);
        Yii::$app->mailer
                ->compose(
                        ['html' => $this->view['html'], 'text' => $this->view['text']], ['msg' => $msg, 'subject' => $subject]
                )
                ->setTo($to)
                ->setSubject($subject)
                ->send();
        // if ($notification->beforeMailSend($message)) {
        //    MailManager::mailNotification($to,$subject,$message,$from);
        // }
        // Yii::$app->i18n->autosetLocale();
    }

    /**
     * @inheritdoc
     */
    public function isActive(User $user = null) {
        // Do not send mail notifications for example content during installlation.
        return Yii::$app->params['installed'];
    }

}
