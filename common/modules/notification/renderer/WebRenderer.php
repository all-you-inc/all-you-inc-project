<?php


namespace common\modules\notification\renderer;

/**
 * The WebTargetRenderer is used to render Notifications for the WebTarget.
 * 
 * @see \common\modules\notification\targets\WebTarget
 * @author buddha
 */
class WebRenderer extends \common\modules\notification\components\rendering\DefaultViewPathRenderer
{

    /**
     * @inheritdoc
     */
    public $defaultView = '@notification/views/default.php';

    /**
     * @inheritdoc
     */
    public $defaultViewPath = '@notification/views';

}
