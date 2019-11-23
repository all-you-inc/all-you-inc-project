<?php

namespace common\modules\customnotification\components;

use common\modules\notification\components\BaseNotification;

/**
 * Notifies a user about something happend
 */
class SignupComission extends BaseNotification
{
    // Module Id (required)
    public $moduleId = "customnotification";

    // Viewname (required)
    public $viewNameHtml = "SignupComission_html";
    public $viewNameText = "SignupComission_text";
    public $title = "Signup Comission";
    public $notificationEventKey = "UserReferralSignupComission";
}