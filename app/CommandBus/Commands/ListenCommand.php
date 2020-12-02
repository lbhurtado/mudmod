<?php

namespace App\CommandBus\Commands;

use App\Models\Contact;

class ListenCommand extends BaseCommand
{
    /** @var Contact */
    public $origin;

    /** @var int */
    public $amount;

    /** @var string */
    public $tags;

    /**
     * ListenCommand constructor.
     *
     * @param Contact $origin
     * @param int $amount
     * @param string $tags i.e. space delimited e.g. word1 word2 word3
     */
    public function __construct(Contact $origin, int $amount,  string $tags)
    {
        $this->origin = $origin;
        $this->amount = $amount;
        $this->tags = $tags;
    }
}
