<?php

use App\CommandBus\{CodesAction, EnlistAction, ListenAction};

$regex_code = ''; $regex_name = '';
$router = resolve('missive:router'); extract(enlist_regex());

$router->register("{pin=\d+} CODES", CodesAction::class);
$router->register("LISTEN {amount=\d+} {tags}", ListenAction::class); //TODO: Add testing for Listen

$router->register("{code={$regex_code}} {name={$regex_name}}", EnlistAction::class);
