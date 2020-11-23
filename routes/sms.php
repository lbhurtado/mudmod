<?php
use LBHurtado\SMS\Facades\SMS;
use LBHurtado\Missive\Actions\TopupMobileAction;
use App\CommandBus\{PingAction, SendAction, LogAction};

$router = resolve('missive:router');

$router->register('LOG {message}', function (string $path, array $values) {
    \Log::info($values['message']);
});

$router->register('PING', app(PingAction::class));
