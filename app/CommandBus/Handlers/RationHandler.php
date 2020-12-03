<?php

namespace App\CommandBus\Handlers;

use App\Jobs\Ration;
use App\CommandBus\Commands\RationCommand;
use Illuminate\Foundation\Bus\DispatchesJobs;

class RationHandler
{
    use DispatchesJobs;

    /**
     * @param RationCommand $command
     */
    public function handle(RationCommand $command)
    {
        tap($command->origin, function ($contact) use ($command) {
            $this->dispatch(new Ration($contact, $command->tags, $command->code));
        });
    }
}
