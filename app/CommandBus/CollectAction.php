<?php

namespace App\CommandBus;

use App\CommandBus\Commands\CollectCommand;
use App\CommandBus\Handlers\CollectHandler;

class CollectAction extends TemplateAction
{
    protected $permission = 'send message';

    protected $command = CollectCommand::class;

    protected $handler = CollectHandler::class;
}
