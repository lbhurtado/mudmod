<?php

use App\CommandBus\{PingAction, ListenAction, VoucherAction};

$router = resolve('missive:router');

$router->register('PING', PingAction::class);
$router->register('LISTEN {tags}', ListenAction::class);
$router->register("VOUCHER {pin}", VoucherAction::class);
