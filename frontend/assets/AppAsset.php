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
        $this->js = ( \Yii::$app->request->url ==  '/shop/product/create' || preg_match('/\/shop\/product\/view/',\Yii::$app->request->url) ) ? [
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
        ];
    }

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.min.css',
        'css/style.min.css',
        'css/custom.css',
    ];
    public $js = [];
    public $depends = [
        'frontend\assets\FontAwesomeAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

}
