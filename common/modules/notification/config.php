<?php

use common\modules\notification\Module;
use common\modules\notification\Events;
use common\modules\user\models\User;
use common\modules\space\models\Space;
use common\commands\IntegrityController;
use common\commands\CronController;
use common\components\ActiveRecord;
use common\widgets\LayoutAddons;

return [
    'id' => 'notification',
    'class' => Module::class,
    'isCoreModule' => true,
    'events' => [
        ['class' => User::class, 'event' => User::EVENT_BEFORE_DELETE, 'callback' => [Events::class, 'onUserDelete']],
        ['class' => Space::class, 'event' => Space::EVENT_BEFORE_DELETE, 'callback' => [Events::class, 'onSpaceDelete']],
        ['class' => IntegrityController::class, 'event' => IntegrityController::EVENT_ON_RUN, 'callback' => [Events::class, 'onIntegrityCheck']],
        ['class' => CronController::class, 'event' => CronController::EVENT_ON_DAILY_RUN, 'callback' => [Events::class, 'onCronDailyRun']],
        ['class' => ActiveRecord::class, 'event' => ActiveRecord::EVENT_BEFORE_DELETE, 'callback' => [Events::class, 'onActiveRecordDelete']],
        ['class' => LayoutAddons::class, 'event' => LayoutAddons::EVENT_BEFORE_RUN, 'callback' => [Events::class, 'onLayoutAddons']]
    ],
];
?>