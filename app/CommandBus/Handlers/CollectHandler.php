<?php

namespace App\CommandBus\Handlers;

use App\Jobs\Collect;
use App\CommandBus\Commands\CollectCommand;
use Illuminate\Foundation\Bus\DispatchesJobs;

class CollectHandler
{
    use DispatchesJobs;

    /**
     * @param CollectCommand $command
     */
    public function handle(CollectCommand $command)
    {
        $this->dispatch(new Collect($command->origin, $command->tag, $command->name));
    }
}
