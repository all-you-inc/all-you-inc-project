<?php

namespace common\modules\notification\jobs;

use common\modules\notification\components\BaseNotification;
use shop\entities\User\User;
use Yii;
use common\modules\queue\ActiveJob;

/**
 * Description of SendNotification
 *
 * @author buddha
 * @since 1.2
 */
class SendNotification extends ActiveJob
{
    /**
     * @var BaseNotification notification instance
     */
    public $notification;

    /**
     * @var int the user id of the recipient
     */
    public $recipientId;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $recipient = User::findOne(['id' => $this->recipientId]);
        d("Jobs >> Send Notifications");
        if ($recipient !== null) {
            Yii::$app->notification->send($this->notification, $recipient);
        }
    }
}
