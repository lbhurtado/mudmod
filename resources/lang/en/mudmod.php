<?php

return [
    'login' => "Login::handle,\n :message \n- :signature",
    'codes' => "Codes::handle,\n :message \n- :signature",
    'enlisted' => "Enlisted::handle,\n :message \n- :signature",
    'rationed' => "Rationed::handle,\n :message \n- :signature",
    'collected' => "Collected::handle,\n :message \n- :signature",
    'verified' => "Verified::handle,\n :message \n- :signature",
    'verify' => "Verify::handle,\n This is your OTP :otp. \n- :signature",
];
