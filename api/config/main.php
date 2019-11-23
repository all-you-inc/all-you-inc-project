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
            'tokenAccessLifetime' => 3600 * 24 * 700,
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
            'enableCookieValidation' => false,
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
                'PUT profile-basic' => 'user/profile/basic-info',
                'POST oauth2/<action:\w+>' => 'oauth2/rest/<action>',

                'GET shop/products/<id:\d+>' => 'shop/product/view',
                'GET shop/collections' => 'shop/category/index',
                'GET shop/products/category/<id:\d+>' => 'shop/product/category',
                'GET shop/products/related/<id:\d+>' => 'shop/product/related',
                'GET shop/products/brand/<id:\d+>' => 'shop/product/brand',
                'GET shop/products/tag/<id:\d+>' => 'shop/product/tag',
                'GET shop/products' => 'shop/product/index',
                'GET shop/products/user-collection' => 'shop/product/user-collection',
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
                // All Countries
                'GET countries' => 'user/profile/get-countries',
                // Add Address
                'POST address' => 'user/profile/add-address',
                // Add Address
                'PUT address/<id:\d+>' => 'user/profile/update-address',
                // Add Address
                'DELETE address/<id:\d+>' => 'user/profile/delete-address',
                // Add Address
                'GET address' => 'user/profile/get-address',
                // GET Brands
                'GET shop/brand/collections' => 'shop/category/get-brands',
                // Create Product
                'POST shop/product/add' => 'shop/product/create',
                // Update Product By Id
                'POST shop/product/update/<id:\d+>' => 'shop/product/update',
                // Update Product Price By Id
                'PUT shop/product/price/<id:\d+>' => 'shop/product/price',
                // Update Product Quantity By Id
                'PUT shop/product/quantity/<id:\d+>' => 'shop/product/quantity',
                // Delete Product By Id
                'DELETE shop/product/<id:\d+>' => 'shop/product/delete',
                // Activate Product By Id
                'POST shop/product/activate/<id:\d+>' => 'shop/product/activate',
                // Draft Product By Id
                'POST shop/product/draft/<id:\d+>' => 'shop/product/draft',
                // Get MemberShip Plans
                'GET plans' => 'user/profile/plan',
                // Get Addons
                'GET addons' => 'user/profile/addons',
                // Get User All Cards
                'GET cards' => 'user/profile/get-cards',
                // Subscribe Plan
                'POST plan' => 'user/purchase/plan',
                // Get User Subscribed Items
                'GET subscribed-Items' => 'user/profile/get-user-subscribed-items',
                // Subscribe Addons
                'POST addons' => 'user/purchase/addons',
                // Get Talents
                'GET talents' => 'user/profile/get-talents',
                // Get Funds
                'GET funds' => 'user/profile/get-funds',
                // Upload Image
                'POST image' => 'user/profile/image',
                // Get All User Talents
                'GET all-talents' => 'user/profile/get-all-talent-users',
                // Get All Gallery Authorized Access
                'GET gallery' => 'user/profile/get-gallery',
                // Get All Public Gallery 
                'GET gallery-public' => 'user/profile/get-public-gallery',
                // Delete Gallery
                'DELETE gallery' => 'user/profile/delete-gallery',
                // Upload Gallery
                'POST gallery' => 'user/profile/upload-gallery',
                // Get My Team
                'GET myteam' => 'user/profile/get-my-team',
                // Add Product Promo Array
                'POST product-promo' => 'shop/product/store-product-promo-session',
                // Get Threads List
                'GET message/threads' => 'user/message/threads',
                // Get Messages of Threads
                'GET message/list' => 'user/message/thread-messages',
                // Send Messages 
                'POST message/threads/create' => 'user/message/create-thread',
                // Send Messages 
                'POST message/create' => 'user/message/create',
                // Get Dashboard
                'GET dashboard' => 'site/dashboard',
                // Update User | Guest Device Token
                'POST site/device/set' => 'site/device-token',
                // Get User Notifications
                'GET user/notifications' => 'user/notification/get-notifications',
                // Mark User Notifications Seen
                'PUT user/notifications/see' => 'user/notification/mark-notifications-seen',
                // Get Notification Settings
                'GET user/notifications/settings' => 'user/notification/get-notification-settings',
                // Update Notification Settings
                'POST user/notifications/update-settings' => 'user/notification/update-notification-settings',
            ],
        ],
    ],
    'as authenticator' => [
        'class' => 'filsh\yii2\oauth2server\filters\auth\CompositeAuth',
        'except' => ['site/index', 'oauth2/rest/token','user/profile/signup',
                    'shop/product/index','shop/product/category','shop/product/related',
                    'user/profile/plan', 'user/profile/get-all-talent-users',
                    'shop/category/index','shop/category/get-brands',
                    'shop/product/view',
                    'user/profile/get-public-gallery',
                    'site/device-token',
                ],
        'authMethods' => [
            ['class' => 'yii\filters\auth\HttpBearerAuth'],
            ['class' => 'yii\filters\auth\QueryParamAuth', 'tokenParam' => 'accessToken'],
        ]
    ],
    'as access' => [
        'class' => 'yii\filters\AccessControl',
        'except' => ['site/index', 'oauth2/rest/token','user/profile/signup' ,
                    'shop/product/index','shop/product/category','shop/product/related',
                    'user/profile/plan', 'user/profile/get-all-talent-users',
                    'shop/category/index','shop/category/get-brands',
                    'shop/product/view',
                    'user/profile/get-public-gallery',
                    'site/device-token',
                    
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
