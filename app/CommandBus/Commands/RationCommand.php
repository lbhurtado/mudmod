<?php

namespace App\CommandBus\Commands;

use App\Models\Contact;

class RationCommand extends BaseCommand
{
    /** @var Contact */
    public $origin;

    /** @var string */
    public $code;

    /** @var string */
    public $tags;

    /**
     * RationCommand constructor.
     *
     * @param Contact $origin
     * @param string $code i.e. MIN, LOW, MID, GEN, MAX
     * @param string $tags i.e. space delimited e.g. word1 word2 word3
     */
    public function __construct(Contact $origin, string $code,  string $tags)
    {
        $this->origin = $origin;
        $this->code = $code;
        $this->tags = $tags;
    }
}
