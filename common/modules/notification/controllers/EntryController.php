<?php

namespace common\modules\notification\controllers;

use Yii;
use yii\web\HttpException;
use common\components\Controller;
use common\components\behaviors\AccessControl;
use common\modules\notification\models\Notification;
use common\components\access\ControllerAccess;

/**
 * EntryController
 *
 * @since 0.5
 */
class EntryController extends Controller
{

    /**
     * @inheritdoc
     */
    public function getAccessRules()
    {
        return [
            [ControllerAccess::RULE_LOGGED_IN_ONLY]
        ];
    }

    /**
     * Redirects to the target URL of the given notification
     */
    public function actionIndex()
    {
        $notificationModel = Notification::findOne(['id' => Yii::$app->request->get('id'), 'user_id' => Yii::$app->user->id]);

        if ($notificationModel === null) {
            throw new HttpException(404, Yii::t('NotificationModule.error','The requested content is not valid or was removed!'));
        }

        $notification = $notificationModel->getBaseModel();

        if ($notification->markAsSeenOnClick) {
            $notification->markAsSeen();
        }

        // Redirect to notification URL
        return $this->redirect($notification->getUrl());
    }

}
