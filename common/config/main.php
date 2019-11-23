<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
        'queue',
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@common/runtime/cache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => '{{%auth_items}}',
            'itemChildTable' => '{{%auth_item_children}}',
            'assignmentTable' => '{{%auth_assignments}}',
            'ruleTable' => '{{%auth_rules}}',
        ],
        'notification' => [
            'class' => 'common\modules\notification\components\NotificationManager',
            'targets' => [
                [
                    'class' => 'common\modules\notification\targets\WebTarget',
                    'renderer' => ['class' => 'common\modules\notification\renderer\WebRenderer']
                ],
                [
                    'class' => 'common\modules\notification\targets\MailTarget',
                    'renderer' => ['class' => 'common\modules\notification\renderer\MailRenderer']
                ],
                [
                    'class' => 'common\modules\notification\targets\MobileTarget'
                ],
            ]
        ],
        // 'queue' => [
        //     'class' => 'yii\queue\file\Queue',
        //     'as log' => 'yii\queue\LogBehavior',
        //     'path' => '@runtime/queue'
        // ],
        'queue' => [
            'class' => 'common\modules\queue\driver\MySQL',
            
        ],
    ],
];  
