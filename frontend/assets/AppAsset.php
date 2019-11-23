<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle {

    public function __construct()
    {
        $search = '/shop/product/view';
        $this->js = ( \Yii::$app->request->url ==  '/addproduct' || preg_match('/\/shop\/product\/view/',\Yii::$app->request->url) ) ? [
            'js/bootstrap.bundle.min.js',
            'js/plugins.min.js',
            'js/main.min.js',
            'js/custom.js',
        ] :
        [
            'js/jquery.min.js',
            'js/bootstrap.bundle.min.js',
            'js/plugins.min.js',
            'js/main.min.js',
            'js/custom.js',
            '//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js',
            '//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js',
            'js/jquery-jvectormap-2.0.3.min.js',
            'js/jquery-jvectormap-world-mill.js',
            'js/jquery-jvectormap-us-aea.js',
            // '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js',
        ];
    }

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.min.css',
        'css/style.min.css',
        'css/custom.css',
        '//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css',
        'css/jquery-jvectormap-2.0.3.css',
        // '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css',
    ];
    public $js = [];
    public $depends = [
        'frontend\assets\FontAwesomeAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

}
