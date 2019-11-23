<?php

namespace common\modules\notification\components;

use common\modules\notification\components\BaseNotification;

/**
 * Notifies a user about something happend
 */
class SomethingHappend extends BaseNotification
{
    // Module Id (required)
    public $moduleId = "notification";

    // Viewname (required)
    public $viewName = "somethingHappend";
}