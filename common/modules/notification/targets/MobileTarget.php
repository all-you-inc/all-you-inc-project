<?php

namespace common\modules\notification\targets;

use common\models\userdevice\UserDevice;
use shop\entities\User\User;
use common\modules\notification\components\BaseNotification;
use Yii;
use yii\di\NotInstantiableException;

//use common\modules\notification\targets\MobileTargetIosProvider;
//use common\modules\notification\targets\MobileTargetAndroidProvider;

/**
 * Mobile Target
 * 
 * @since 1.2
 * @author buddha
 */
class MobileTarget extends BaseTarget {

    /**
     * @inheritdoc
     */
    public $id = 'mobile';

    /**
     * @var MobileTargetProvider
     * @var MobileTargetIosProvider
     * @var MobileTargetAndroidProvider
     */
    public $providers = [];

    public function init() {
        parent::init();

        try {
            $this->providers['ios'] = Yii::$container->get(MobileTargetIosProvider::class);
            $this->providers['android'] = Yii::$container->get(MobileTargetAndroidProvider::class);
            d('In $this->providers ==> ');
            d($this->providers);
        } catch (NotInstantiableException $e) {
            // No provider given
        }
    }

    /**
     * Used to forward a BaseNotification object to a BaseTarget.
     * The notification target should handle the notification by pushing a Job to
     * a Queue or directly handling the notification.
     * 
     * @param BaseNotification $notification
     */
    public function handle(BaseNotification $notification, User $user) {
        d('In Mobile Target');
//        d('$user->id');
//        d($user->id);
        $msg = Yii::$app->controller->renderPartial('@common/modules/' . $notification->moduleId . '/views/' . $notification->viewNameText, ['originator' => $notification->originator, 'user' => $user, 'source' => $notification->source]);
//        d('$msg ==> ');
//        d($msg);
// d('$provider ==> ');
// d($this->providers['ios']);
// d($this->providers['android']);
        if (count($this->providers) > 0) {
            foreach ($this->providers as $key => $provider) {
                d('----------in $provider-----------');
                d($provider);
//                d('$provider ==> ');
//                d($provider);
                $type = $key == 'ios' ? 'apns' : 'fcm';
//                d($type);
                $user_devices = UserDevice::find()->where(['user_id' => $user->id, 'type' => $type])->all();
                d('$user_devices count ==> ');
                d(count($user_devices));
                if ($user_devices) {
                    foreach ($user_devices as $device) {
                        $provider->handle($device->token, $msg, $notification->title, $notification->source->id, $notification->notificationEventKey);
                    }
                } else {
                    d('user device not found!!');
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getTitle() {
        return 'Mobile';
        return Yii::t('NotificationModule.targets', 'Mobile');
    }

    public function isActive(User $user = null) {
        if (!$this->provider) {
            return false;
        }

        return $this->provider->isActive($user);
    }

}
