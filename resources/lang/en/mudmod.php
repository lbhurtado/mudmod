<?php

return [
    'feedback' => [
        'notify' => ":handle,\n\n:message\n\n- :signature",
        'message' => "Keyword: :keyword\nAmount: :amount"
        ],
    'voucher' => "Vouchers::handle,\n :message \n- :signature",
    'listen' => "Listened: :handle,\n :message \n- :signature",
    'relay' => "Relayed: :handle,\n :message \n- :signature",
];
