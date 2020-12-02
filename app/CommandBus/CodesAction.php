<?php

namespace App\CommandBus;

use App\CommandBus\Commands\CodesCommand;
use App\CommandBus\Handlers\CodesHandler;

class CodesAction extends TemplateAction
{
    protected $permission = 'send message';

    protected $command = CodesCommand::class;

    protected $handler = CodesHandler::class;
}
