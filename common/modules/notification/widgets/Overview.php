<?php

namespace common\modules\notification\widgets;

use Yii;
use common\widgets\JsWidget;
use common\modules\notification\controllers\ListController;
use yii\helpers\Url;

/**
 * Notificaiton overview widget.
 *
 * @author buddha
 * @since 1.1
 */
class Overview extends JsWidget
{
    public $id = 'notification_widget';

    public function init()
    {
        $this->view->registerJsConfig('notification', [
            'icon' => $this->view->theme->getBaseUrl().'/ico/notification-o.png',
            'loadEntriesUrl' => Url::to(['/notification/list']),
            'sendDesktopNotifications' => boolval(Yii::$app->notification->getDesktopNoficationSettings(Yii::$app->user->getIdentity())),
            'text' =>  [
                'placeholder' => Yii::t('NotificationModule.widgets_views_list', 'There are no notifications yet.')
            ]
        ]);

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (Yii::$app->user->isGuest) {
            return '';
        }

        return $this->render('overview', [
            'options' => $this->getOptions()
        ]);
    }

    public function getAttributes()
    {
        return [
            'id' => 'notification_widget',
            'class' => "btn-group"
        ];
    }

    public function getData()
    {
        return [
            'ui-init' => ListController::getUpdates(),
            'ui-widget' => "notification.NotificationDropDown"
        ];
    }
}

?>