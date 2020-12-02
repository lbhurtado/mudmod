<?php

return [
    'country' => env('DEFAULT_COUNTRY','PH'),
    'relay' => [
        'log'       => env('RELAY_LOG',       true),
        'email'     => env('RELAY_EMAIL',     true),
        'mobile'    => env('RELAY_MOBILE',    true),
        'reply'     => env('RELAY_REPLY',     true),
        'hashtags'  => env('RELAY_HASHTAGS',  true),
        'converse'  => env('RELAY_CONVERSE',  true),
    ],
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
    'signature' => env('SIGNATURE', 'mudmod'),
];
