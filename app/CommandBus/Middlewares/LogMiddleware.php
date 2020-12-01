<?php

namespace App\CommandBus\Middlewares;

use Log;
use League\Tactician\Middleware;

class LogMiddleware implements Middleware
{
    public function execute($command, callable $next)
    {
        Log::info("******************************");
        Log::info($command);
        Log::info("******************************");

        $next($command);
    }
}
