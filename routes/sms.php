<?php

use App\CommandBus\{CodesAction, EnlistAction, RationAction, CollectAction};

$regex_code = ''; $regex_name = '';
$router = resolve('missive:router'); extract(enlist_regex());
$router->register("{pin=\d+} CODES", CodesAction::class);
$router->register("#{tag=\w+} {name}", CollectAction::class);

parse_str(config('mudmod.rations.default'), $rations); //TODO: check in DB as well
tap(implode('|', array_keys($rations)), function ($codes) use ($router) {
    $router->register("RATION {code={$codes}} {tags}", RationAction::class);//TODO: Add testing for Ration
});

$router->register("{code={$regex_code}} {name={$regex_name}}", EnlistAction::class);
