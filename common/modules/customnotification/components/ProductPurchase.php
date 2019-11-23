<?php

namespace common\modules\customnotification\components;

use common\modules\notification\components\BaseNotification;

/**
 * Notifies a user about something happend
 */
class ProductPurchase extends BaseNotification {

    // Module Id (required)
    public $moduleId = "customnotification";
    // Viewname (required)
    public $viewNameHtml = "ProductPurchase_html";
    public $viewNameText = "ProductPurchase_text";
    public $title = "Product Purchase";
    public $notificationEventKey = "UserProductPurchase";

}
