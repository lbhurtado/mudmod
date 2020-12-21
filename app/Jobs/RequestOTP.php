<?php

namespace App\Jobs;

use App\Notifications\Verify;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RequestOTP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $contact;

    private $otp;

    /**
     * RequestOTP constructor.
     *
     * @param $contact
     * @param $otp
     */
    public function __construct($contact, $otp)
    {
        $this->contact = $contact;
        $this->otp = $otp;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->contact->notify(new Verify($this->otp));
    }
}
