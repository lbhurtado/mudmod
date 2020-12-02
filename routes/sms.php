<?php

use App\CommandBus\{CodesAction, EnlistAction, ListenAction, AllocateAction};

$regex_code = ''; $regex_name = '';
$router = resolve('missive:router'); extract(enlist_regex());

$router->register("{pin=\d+} CODES", CodesAction::class);
$router->register("LISTEN {amount=\d+} {tags}", ListenAction::class); //TODO: remove this
$router->register("ALLOCATE {amount=\d+} {tags}", AllocateAction::class); //TODO: Add testing for Allocation

$router->register("{code={$regex_code}} {name={$regex_name}}", EnlistAction::class);
