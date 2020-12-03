<?php

namespace App\CommandBus;

use App\Traits\HasOptionalMiddlewares;
use App\CommandBus\Commands\RelayCommand;
use App\CommandBus\Handlers\RelayHandler;

class RelayAction extends TemplateAction
{
    use HasOptionalMiddlewares;

    protected $permission = 'send message';

    protected $command = RelayCommand::class;

    protected $handler = RelayHandler::class;

    public function setup()
    {
        parent::setup();

        $this->addSMSToData();
    }
}
