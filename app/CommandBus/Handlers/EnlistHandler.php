<?php

namespace App\CommandBus\Handlers;

use App\Jobs\Enlist;
use App\CommandBus\Commands\EnlistCommand;
use Illuminate\Foundation\Bus\DispatchesJobs;

class EnlistHandler
{
    use DispatchesJobs;

    /**
     * @param EnlistCommand $command
     */
    public function handle(EnlistCommand $command)
    {
        $this->dispatch(new Enlist($command->origin, $command->code, $command->name));
    }
}
