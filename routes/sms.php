<?php

use App\CommandBus\{CodesAction, EnlistAction};

$regex_code = ''; $regex_name = '';
$router = resolve('missive:router'); extract(enlist_regex());

$router->register("{pin=\d+} CODES", CodesAction::class);


$router->register("{code={$regex_code}} {name={$regex_name}}", EnlistAction::class);
