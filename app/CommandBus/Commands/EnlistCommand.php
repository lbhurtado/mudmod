<?php

namespace App\CommandBus\Commands;

use App\Models\Contact;

class EnlistCommand extends BaseCommand
{
    /**
     * @var Contact
     */
    public $origin;

    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $name;

    /**
     * EnlistCommand constructor.
     *
     * @param Contact $origin
     * @param string $code
     * @param string $name
     */
    public function __construct(Contact $origin, string $code, string $name)
    {
        $this->origin = $origin;
        $this->code = $code;
        $this->name = $name;
    }
}
