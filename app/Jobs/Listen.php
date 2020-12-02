<?php

namespace App\Jobs;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Listen implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Contact */
    public $contact;

    /** @var string */
    public $tags;

    /** @var int */
    public $amount;

    /**
     * Listen constructor.
     *
     * @param Contact $contact
     * @param string $tags
     * @param int $amount
     */
    public function __construct(Contact $contact, string $tags, int $amount)
    {
        $this->contact = $contact;
        $this->tags = $tags;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->contact->catch($this->amount, $this->getHashtags($this->tags));
    }

    /**
     * @param string $tags
     * @return array
     */
    protected function getHashtags(string $tags): array
    {
        return explode(' ', $tags);
    }
}
