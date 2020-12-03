<?php

namespace App\Jobs;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Ration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Contact */
    public $contact;

    /** @var string */
    public $tags;

    /** @var string */
    public $code;

    /**
     * Listen constructor.
     *
     * @param Contact $contact
     * @param string $tags
     * @param string $code
     */
    public function __construct(Contact $contact, string $tags, string $code)
    {
        $this->contact = $contact;
        $this->tags = $tags;
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->contact->ration($this->code, $this->getHashtags($this->tags));
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
