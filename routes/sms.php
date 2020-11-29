<?php

use App\CommandBus\{PingAction, ListenAction};

$router = resolve('missive:router');

$router->register('PING', PingAction::class);
$router->register('LISTEN {tags}', ListenAction::class);
