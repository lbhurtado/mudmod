<?php

namespace App\CommandBus;

use App\CommandBus\Commands\KeywordCommand;
use App\CommandBus\Handlers\KeywordHandler;

class KeywordAction extends TemplateAction
{
    /** @var string */
    protected $permission = 'send message';

    protected $command = KeywordCommand::class;

    protected $handler = KeywordHandler::class;
}
