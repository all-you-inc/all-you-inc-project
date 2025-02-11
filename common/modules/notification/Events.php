<?php

namespace common\modules\notification;

use common\components\Event;
use common\modules\user\models\User;
use Yii;
use common\modules\notification\models\Notification;

/**
 * Events provides callbacks for all defined module events.
 *
 * @author luke
 */
class Events extends \yii\base\BaseObject
{

    /**
     * On User delete, also delete all posts
     *
     * @param Event $event
     */
    public static function onUserDelete($event)
    {
        /** @var User $user */
        $user = $event->sender;

        foreach (Notification::findAll(['user_id' => $user->id]) as $notification) {
            $notification->delete();
        }

        foreach (Notification::findAll(['originator_user_id' => $user->id]) as $notification) {
            $notification->delete();
        }

        foreach (Notification::findAll(['source_class' => User::class, 'source_pk' => $user->id]) as $notification) {
            $notification->delete();
        }

        return true;
    }

    /**
     * On workspace deletion make sure to delete all posts
     *
     * @param type $event
     */
    public static function onSpaceDelete($event)
    {

        foreach (Notification::findAll(['space_id' => $event->sender->id]) as $notification) {
            $notification->delete();
        }
    }

    /**
     * Callback to validate module database records.
     *
     * @param Event $event
     */
    public static function onIntegrityCheck($event)
    {

        $integrityChecker = $event->sender;
        $integrityChecker->showTestHeadline("Notification Module (" . Notification::find()->count() . " entries)");

        foreach (Notification::find()->joinWith(['space', 'user'])->each() as $notification) {
            /** @var Notification $notification */

            // Check if Space still exists
            if ($notification->space_id != "" && $notification->space == null) {
                if ($integrityChecker->showFix("Deleting notification id " . $notification->id . " workspace seems to no longer exist!")) {
                    $notification->delete();
                }
            }

            // Check if source object exists when defined
            try {
                if ($notification->source_class != "" && $notification->getSourceObject() == null) {
                    if ($integrityChecker->showFix("Deleting notification id " . $notification->id . " source class set but seems to no longer exist!")) {
                        $notification->delete();
                    }
                }
            } catch(\Exception $e) {
                // Handles errors for getSourceObject() calls
                if ($integrityChecker->showFix("Deleting notification id " . $notification->id . " source class set but seems to no longer exist!")) {
                    $notification->delete();
                }
            }

            // Check if target user exists
            if ($notification->user == null) {
                if ($integrityChecker->showFix("Deleting notification id " . $notification->id . " target user seems to no longer exist!")) {
                    $notification->delete();
                }
            }

            // Check if target user exists
            if (!class_exists($notification->class)) {
                if ($integrityChecker->showFix("Deleting notification id " . $notification->id . " without valid class!")) {
                    $notification->delete();
                }
            }

            // Check if module id is set
            if ($notification->module == "") {
                if ($integrityChecker->showFix("Deleting notification id " . $notification->id . " without valid module!")) {
                    $notification->delete();
                }
            }

            if (!empty($notification->originator_user_id) && $notification->originator === null) {
                if ($integrityChecker->showFix("Deleting notification id " . $notification->id . " without valid originator!")) {
                    $notification->delete();
                }
            }


        }
    }

    /**
     * On run of the cron, do some cleanup stuff.
     * We delete all notifications which are older than 2 month and are seen.
     *
     * @param type $event
     */
    public static function onCronDailyRun($event)
    {
        $controller = $event->sender;

        $controller->stdout("Deleting old notifications... ");
        /**
         * Delete seen notifications which are older than 2 months
         */
        $deleteTime = time() - (60 * 60 * 24 * 31 * 2); // Notifcations which are older as ~ 2 Months
        foreach (Notification::find()->where(['seen' => 1])->andWhere(['<', 'created_at', date('Y-m-d', $deleteTime)])->all() as $notification) {
            $notification->delete();
        }
        $controller->stdout('done.' . PHP_EOL, \yii\helpers\Console::FG_GREEN);
    }

    public static function onActiveRecordDelete($event)
    {
        models\Notification::deleteAll([
            'source_class' => $event->sender->className(),
            'source_pk' => $event->sender->getPrimaryKey(),
        ]);
    }

    public static function onLayoutAddons($event)
    {
        if(Yii::$app->request->isPjax) {
            $event->sender->addWidget(widgets\UpdateNotificationCount::class);
        }
    }

}
