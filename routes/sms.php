<?php

use App\CommandBus\{VoucherAction, RedeemAction};

$router = resolve('missive:router'); $regex_code = ''; $regex_email = ''; extract(redeem_regex());

$router->register("VOUCHER {pin}", VoucherAction::class);
$router->register("{code={$regex_code}} {email={$regex_email}}", RedeemAction::class);
