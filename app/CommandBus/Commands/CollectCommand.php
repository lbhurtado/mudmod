<?php

namespace App\CommandBus\Commands;

use App\Models\Contact;

class CollectCommand extends BaseCommand
{
    /**
     * @var Contact
     */
    public $origin;

    /**
     * @var string
     */
    public $tag;

    /**
     * @var string
     */
    public $name;

    /**
     * CollectCommand constructor.
     *
     * @param Contact $origin
     * @param string $tag
     * @param string $name
     */
    public function __construct(Contact $origin, string $tag, string $name)
    {
    	$this->origin = $origin;
    	$this->tag = $tag;
    	$this->name = $name;
    }
}
