<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@staticRoot' => $params['staticPath'],
        '@static'   => $params['staticHostInfo'],
    ],
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => [
        'log',
        'common\bootstrap\SetUp',
        [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => 'json',
                'application/xml' => 'xml',
            ],
        ],
    ],
    'modules' => [
        'oauth2' => [
            'class' => 'filsh\yii2\oauth2server\Module',
            'components' => [ 
                'request' => function () { 
                    return \filsh\yii2\oauth2server\Request::createFromGlobals();
                },
                'response' => [
                    'class' => \filsh\yii2\oauth2server\Response::class,
                ],
            ],
            'tokenParamName' => 'accessToken',
            'tokenAccessLifetime' => 3600 * 24,
            'storageMap' => [
                'user_credentials' => 'common\auth\Identity',
            ],
            'grantTypes' => [
                'user_credentials' => [
                    'class' => 'OAuth2\GrantType\UserCredentials',
                ],
                'refresh_token' => [
                    'class' => 'OAuth2\GrantType\RefreshToken',
                    'always_issue_new_refresh_token' => true
                ]
            ]
        ]
    ],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'formatters' => [
                'json' => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\auth\Identity',
            'enableAutoLogin' => false,
            'enableSession' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'GET profile' => 'user/profile/index',
                'POST oauth2/<action:\w+>' => 'oauth2/rest/<action>',

                'GET shop/products/<id:\d+>' => 'shop/product/view',
                'GET shop/collections' => 'shop/category/index',
                'GET shop/products/category/<id:\d+>' => 'shop/product/category',
                'GET shop/products/related/<id:\d+>' => 'shop/product/related',
                'GET shop/products/brand/<id:\d+>' => 'shop/product/brand',
                'GET shop/products/tag/<id:\d+>' => 'shop/product/tag',
                'GET shop/products' => 'shop/product/index',
                'shop/products/<id:\d+>/cart' => 'shop/cart/add',
                'shop/products/<id:\d+>/wish' => 'shop/wishlist/add',

                'GET shop/cart' => 'shop/cart/index',
                'GET shop/orders' => 'shop/order/list',
                'DELETE shop/cart' => 'shop/cart/clear',
                'shop/cart/checkout' => 'shop/checkout/index',
                'PUT shop/cart/<id:\w+>/quantity' => 'shop/cart/quantity',
                'DELETE shop/cart/<id:\w+>' => 'shop/cart/delete',

                'GET shop/wishlist' => 'shop/wishlist/index',
                'DELETE shop/wishlist/<id:\d+>' => 'shop/wishlist/delete',

                // all industry
                'GET industry' => 'user/profile/industry',
                // talent by industry id
                'GET talent/<id:\d+>' => 'user/profile/talent',
                // all DJ genre
                'GET djgenre' => 'user/profile/djgenre', 
                // all Music genre
                'GET musicgenre' => 'user/profile/musicgenre',
                // all instrument
                'GET instrument' => 'user/profile/instrument',
                // instrumentSpecification by instrument id
                'GET instrumentspecification/<id:\d+>' => 'user/profile/instrumentspecification',
                // Sign Up
                'POST signup' => 'user/profile/signup',
                // Update Profile (Industry | Talent)
                'POST profile' => 'user/profile/profile',
                // Update Profile (Industry | Talent)
                'PUT profile' => 'user/profile/profile',
            ],
        ],
    ],
    'as authenticator' => [
        'class' => 'filsh\yii2\oauth2server\filters\auth\CompositeAuth',
        'except' => ['site/index', 'oauth2/rest/token','user/profile/signup',
                    'shop/product/index','shop/product/category','shop/product/related',
                    'shop/category/index'],
        'authMethods' => [
            ['class' => 'yii\filters\auth\HttpBearerAuth'],
            ['class' => 'yii\filters\auth\QueryParamAuth', 'tokenParam' => 'accessToken'],
        ]
    ],
    'as access' => [
        'class' => 'yii\filters\AccessControl',
        'except' => ['site/index', 'oauth2/rest/token','user/profile/signup' ,
                    'shop/product/index','shop/product/category','shop/product/related',
                    'shop/category/index'
                    ],
        'rules' => [
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
    ],
    'as exceptionFilter' => [
        'class' => 'filsh\yii2\oauth2server\filters\ErrorToExceptionFilter',
    ],
    'params' => $params,
];
