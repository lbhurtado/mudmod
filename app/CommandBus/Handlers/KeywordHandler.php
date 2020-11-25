<?php

namespace App\CommandBus\Handlers;

use App\Notifications\Feedback;
use App\CommandBus\Commands\KeywordCommand;

class KeywordHandler
{
    /**
     * @param KeywordCommand $command
     */
    public function handle(KeywordCommand $command)
    {
        tap($command->origin, function ($contact) use ($command) {

            $contact->magpamudmod($command->keyword, $command->amount);
        })
            ->notify(new Feedback($this->getMessage($command)));
    }

    /**
     * @param KeywordCommand $command
     * @return string
     */
    protected function getMessage(KeywordCommand $command): string
    {
        return trans('mudmod.feedback.message', [
            'keyword' => $command->keyword,
            'amount' => $command->amount
        ]);
    }
}
