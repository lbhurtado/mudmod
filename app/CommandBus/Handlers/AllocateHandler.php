<?php

namespace App\CommandBus\Handlers;

use App\Jobs\Allocate;
use App\CommandBus\Commands\AllocateCommand;
use Illuminate\Foundation\Bus\DispatchesJobs;

class AllocateHandler
{
    use DispatchesJobs;

    /**
     * @param AllocateCommand $command
     */
    public function handle(AllocateCommand $command)
    {
        tap($command->origin, function ($contact) use ($command) {
            $this->dispatch(new Allocate($contact, $command->tags, $command->amount));
        });
    }
}
