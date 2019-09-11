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
    ],
    'squarePaymentGateWay' =>[
        'application-id' => '< APPLICATION-ID >',
        'location-id' => '< LOCATION-ID >',
        'access-token' => '< ACCESS-TOKEN >',
        'host' => 'https://connect.squareup.com',
        'paymentform' => 'https://js.squareup.com/v2/paymentform',

        'sandBox-application-id' => 'sandbox-sq0idb-apT3BZxK1FV_vp438rah2A',
        'sandBox-location-id' => 'KWBMPZCRK0EXM',
        'sandBox-access-token' => 'EAAAEGTRSlZDiCtHB9-fPIlyi6uzC2blwHeIkrgSXrwmKlaF8WqmhRHciHPGwO-o',
        'sandBox-host' => 'https://connect.squareupsandbox.com',
        'sandBox-paymentform' => 'https://js.squareupsandbox.com/v2/paymentform',

        'secret-token' => '< SECRET-TOKEN >',
    ],
];
