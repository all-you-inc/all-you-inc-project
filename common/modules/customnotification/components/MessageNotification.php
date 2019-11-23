<?php

namespace common\modules\customnotification\components;

use common\modules\notification\components\BaseNotification;

/**
 * Notifies a user about something happend
 */
class MessageNotification extends BaseNotification
{
    // Module Id (required)
    public $moduleId = "customnotification";

    // Viewname (required)
    public $viewNameHtml = "MessageNotification_html";
    public $viewNameText = "MessageNotification_text";
    public $title = "Message";
    public $notificationEventKey = "InternalMessageRecevied";
}