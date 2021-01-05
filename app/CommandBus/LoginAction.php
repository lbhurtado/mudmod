<?php

namespace App\CommandBus;

use App\CommandBus\Commands\LoginCommand;
use App\CommandBus\Handlers\LoginHandler;

class LoginAction extends TemplateAction
{
    protected $permission = 'send message';

    protected $command = LoginCommand::class;

    protected $handler = LoginHandler::class;
}
