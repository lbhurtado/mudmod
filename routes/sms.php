<?php

use App\CommandBus\{PingAction, KeywordAction};

$router = resolve('missive:router');

$router->register('LOG {message}', function (string $path, array $values) {
    \Log::info($values['message']);
});

$router->register('PING', PingAction::class);
