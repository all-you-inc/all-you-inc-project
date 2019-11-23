<?php


namespace common\modules\notification\targets;


use common\modules\notification\components\BaseNotification;
use shop\entities\User\User;

interface MobileTargetProvider
{
    /**
     * @param BaseNotification $notification
     * @param User $user
     * @return boolean
     */
    public function handle($device_token, $msg = null, $title = null, $pk, $key, $params = []);

    /**
     * @param User|null $user
     * @return boolean
     */
//    public function isActive(User $user = null);
}
