<?php

namespace App\CommandBus\Commands;

use App\Models\Contact;

class KeywordCommand extends BaseCommand
{
    /** @var Contact */
    public $origin;

    /** @var string */
    public $keyword;

    /** @var int */
    public $amount;

    /**
     * KeywordCommand constructor.
     * @param Contact $origin
     * @param string $keyword
     * @param int $amount
     */
    public function __construct(Contact $origin, string $keyword, int $amount)
    {
        $this->origin = $origin;
        $this->keyword = $keyword;
        $this->amount = $amount;
    }
}
