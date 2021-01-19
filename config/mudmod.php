<?php

return [
    'country' => env('DEFAULT_COUNTRY','PH'),
    'permissions' => [
        'admin'      => ['send message', 'issue command', 'broadcast message'],
        'agent'      => ['issue command'],
        'cashier'    => ['issue command'],
        'subscriber' => ['send message' ],
    ],
    'vouchers' => [
        'admin'      => env('ADMIN_VOUCHERS',       1),
        'agent'      => env('AGENT_VOUCHERS',       5),
        'cashier'    => env('CASHIER_VOUCHERS',     5),
        'subscriber' => env('SUBSCRIBER_VOUCHERS',  5),
    ],
    'rations' => [
        'default' => env('DEFAULT_RATIONS',  'MIN=100&LOW=200&MID=300&GEN=400&MAX=500'),
    ],
    'signature' => env('SIGNATURE', 'mudmod'),
    'login' => [
        'user' => env('DEFAULT_LOGIN_USERID',1),
        'redirect' => env('DEFAULT_LOGIN_REDIRECTURL','/dashboard'),
    ],
    'otp' => [
        'bypass' =>   env('OTP_BYPASS',0),
    ],
];
