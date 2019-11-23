<?php

namespace common\modules\notification\components;

use function GuzzleHttp\json_decode;
use Yii;
use Zend\Http\ClientStatic;

class MailManager
{

    const mailNotification = "https://api.leapture.com/notification/email";

    public static function mailNotification($address, $subject , $message ,$from = 'support@common.com')
    {
        $data = [
            'from' => $from,
            'address' => $address,
            'text' => $message,
            'subject' => $subject,
        ];

        $json = json_encode($data);

        $response = ClientStatic::post(
            static::mailNotification,
            ['data' => null],
            ['Content-Type'=>'application/json'],
            $json
        );

        if ($response->isSuccess()) {
            return true;
        }
        return false;
    }
}
