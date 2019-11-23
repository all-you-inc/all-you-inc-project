<?php


namespace common\modules\notification\widgets;

use Yii;

/**
 * UpdateNotificationCount widget is an LayoutAddon widget for updating the notification count
 * and is only used if pjax is active.
 *
 * @author buddha
 * @since 1.2
 */
class UpdateNotificationCount extends \yii\base\Widget
{

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        return $this->render('updateNotificationCount', [
            'count' => \common\modules\notification\models\Notification::findUnseen()->count()
        ]);
    }
}
