<?php


namespace common\modules\notification\assets;

use yii\web\AssetBundle;

class NotificationAsset extends AssetBundle
{

    public $sourcePath = '@notification/resources';
    public $css = [];
    public $js = [
        'js/common.notification.js'
    ];
}
