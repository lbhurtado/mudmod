<?php

namespace App\CommandBus;

use App\CommandBus\Commands\RationCommand;
use App\CommandBus\Handlers\RationHandler;

class RationAction extends TemplateAction
{
    protected $permission = 'issue command';

    protected $command = RationCommand::class;

    protected $handler = RationHandler::class;
}
