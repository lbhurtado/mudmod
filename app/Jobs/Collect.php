<?php

namespace App\Jobs;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Collect implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Contact */
    public $contact;

    /** @var string */
    public $tag;

    /** @var string */
    public $name;

    /**
     * Redeem constructor.
     *
     * @param Contact $contact
     * @param string $tag - generated voucher code for Ration
     * @param string $name - name of redeemer
     */
    public function __construct(Contact $contact, string $tag, string $name)
    {
        $this->contact = $contact;
        $this->tag = $tag;
        $this->name = $name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->contact->collectRation($this->tag, $this->name);
    }
}
