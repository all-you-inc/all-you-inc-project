<?php

/** @var array $params */

return [
    'class' => 'yii\web\UrlManager',
    'hostInfo' => $params['frontendHostInfo'],
    'baseUrl' => '',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'cache' => false,
    'rules' => [
        '' => 'site/index',
        'dashboard' => 'user/dashboard',
        'profile' => 'user/profile',
        'addaddress' => 'user/addaddress',
        'updateaddress' => 'user/updateaddress',
        'updateprofile' => 'user/updateprofile',
        'orders' => 'cabinet/order',
        'sales' => 'cabinet/sales',
        'products' => 'shop/product/index',
        'contact' => 'contact/index',
        'cart' => 'shop/cart/index',
        'signup' => 'auth/signup/plan',
        'signup/<_a:[\w-]+>' => 'auth/signup/<_a>',
        '<_a:login|logout>' => 'auth/auth/<_a>',

        'blog' => 'blog/post/index',
        'blog/tag/<slug:[\w\-]+>' => 'blog/post/tag',
        'blog/<id:\d+>' => 'blog/post/post',
        'blog/<id:\d+>/comment' => 'blog/post/comment',
        'blog/<slug:[\w\-]+>' => 'blog/post/category',

        'catalog' => 'shop/catalog/index',
        ['class' => 'frontend\urls\CategoryUrlRule'],
        'catalog/<id:\d+>' => 'shop/catalog/product',

        'cabinet' => 'cabinet/default/index',
        'cabinet/<_c:[\w\-]+>' => 'cabinet/<_c>/index',
        'cabinet/<_c:[\w\-]+>/<id:\d+>' => 'cabinet/<_c>/view',
        'cabinet/<_c:[\w\-]+>/<_a:[\w-]+>' => 'cabinet/<_c>/<_a>',
        'cabinet/<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => 'cabinet/<_c>/<_a>',

        ['class' => 'frontend\urls\PageUrlRule'],

        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
    ],
];