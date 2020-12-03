<?php

use App\Models\Ration;
use BeyondCode\Vouchers\Models\Voucher;
use App\CommandBus\{CodesAction, EnlistAction, RationAction, RelayAction};

$regex_code = ''; $regex_name = '';
$router = resolve('missive:router'); extract(enlist_regex());

optional(implode('|', Voucher::where('model_type', Ration::class)->pluck('code')->toarray()), function ($codes) use ($router) {
    $router->register("#{code={$codes}} {name}", RelayAction::class);
});

$router->register('{message}', RelayAction::class);
$router->register("{pin=\d+} CODES", CodesAction::class);

parse_str(config('mudmod.rations.default'), $rations); //TODO: check in DB as well
tap(implode('|', array_keys($rations)), function ($codes) use ($router) {
    $router->register("RATION {code={$codes}} {tags}", RationAction::class);//TODO: Add testing for Ration
});

$router->register("{code={$regex_code}} {name={$regex_name}}", EnlistAction::class);
