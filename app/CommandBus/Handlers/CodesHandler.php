<?php

namespace App\CommandBus\Handlers;

use Setting;
use App\Notifications\Codes;
use App\CommandBus\Commands\CodesCommand;
use BeyondCode\Vouchers\Models\Voucher as Vouchers;

class CodesHandler
{
    /**
     * @param CodesCommand $command
     */
    public function handle(CodesCommand $command)
    {
        if ($command->pin == Setting::get('PIN')) {
            $command->origin->notify(new Codes($this->getMessage()));
        }
    }

    protected function getMessage()
    {
        $text = "\n";

        Vouchers::all()->map(function($voucher) {
            return $voucher->code . ' ' . $voucher->model->handle;
        })->each(function ($items) use (&$text) {
            $text .= $items . "\n";
        });

        return $text;
    }
}
