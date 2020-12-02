<?php

namespace App\CommandBus;

use App\CommandBus\Commands\EnlistCommand;
use App\CommandBus\Handlers\EnlistHandler;

class EnlistAction extends TemplateAction
{
    protected $permission = 'send message';

    protected $command = EnlistCommand::class;

    protected $handler = EnlistHandler::class;
}
