<?php
return [
    'adminEmail' => 'admin@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'user.rememberMeDuration' => 3600 * 24 * 30,
    'cookieDomain' => '.allyouinc.siliconplex',
    'frontendHostInfo' => 'http://allyouinc.siliconplex',
    'backendHostInfo' => 'http://admin.allyouinc.siliconplex',
    'staticHostInfo' => 'http://static.allyouinc.siliconplex',
    'staticPath' => dirname(__DIR__, 2) . '/static',
    'mailChimpKey' => '9d5db345127c058c943c09b3aebfb744-us3',
    'mailChimpListId' => '0d003a2dac',
    'smsRuKey' => '',
    'reCaptcha' => [
        'secret-key' => '6LdugLQUAAAAAFZYesN7Q-B-dJuWgh-2XOWN6uo2',
        'site-key' => '6LdugLQUAAAAAP9k-TGv9cjv0P7BEUvLbkczOpd3'
    ]
];
