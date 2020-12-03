<?php

use App\Models\Ration;
use App\CommandBus\{CodesAction, EnlistAction, RationAction, ListenAction};

$regex_code = ''; $regex_name = '';
$router = resolve('missive:router'); extract(enlist_regex());

$router->register("{pin=\d+} CODES", CodesAction::class);
$router->register("LISTEN {amount=\d+} {tags}", ListenAction::class); //TODO: remove this

tap(implode('|', Ration::pluck('code')->toArray()), function ($codes) use ($router) {
    $router->register("RATION {code={$codes}} {tags}", RationAction::class);//TODO: Add testing for Ration
});

$router->register("{code={$regex_code}} {name={$regex_name}}", EnlistAction::class);
