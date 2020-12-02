<?php

namespace App\CommandBus;

use App\CommandBus\Commands\AllocateCommand;
use App\CommandBus\Handlers\AllocateHandler;

class AllocateAction extends TemplateAction
{
    protected $permission = 'issue command';

    protected $command = AllocateCommand::class;

    protected $handler = AllocateHandler::class;
}
