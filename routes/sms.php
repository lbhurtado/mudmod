<?php

use App\Models\{Ration, Role, Contact};
use App\CommandBus\{CodesAction, EnlistAction, RationAction, CollectAction};

$regex_code = ''; $regex_name = '';
$router = resolve('missive:router');
$router->register("{pin=\d+} CODES", CodesAction::class);

if (Schema::hasTable('vouchers')) {
    $regex_tags = Ration::tagsRegex();
    $regex_name = Contact::nameRegex();
    $router->register("#{tag={$regex_tags}} {name={$regex_name}}", CollectAction::class);
}

parse_str(config('mudmod.rations.default'), $rations); //TODO: check in DB as well
tap(implode('|', array_keys($rations)), function ($codes) use ($router) {
    $router->register("RATION {code={$codes}} {tags}", RationAction::class);//TODO: Add testing for Ration
});

if (Schema::hasTable('vouchers')) {
    $regex_code = Role::codeRegex();
    $regex_name = Contact::nameRegex();
    $router->register("{code={$regex_code}} {name={$regex_name}}", EnlistAction::class);
}

$regex_json = '';
extract(mudmod_regex());

$router->register("MUDMOD", function(string $path, array $values) {
    dd('Hello World!');
});

