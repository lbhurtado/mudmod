<?php

namespace App\Jobs;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Enlist implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Contact */
    public $contact;

    /** @var string */
    public $code;

    /** @var string */
    public $handle;

    /**
     * Enlist constructor.
     *
     * @param Contact $contact
     * @param string $code - generated voucher code for Role
     * @param string $handle
     */
    public function __construct(Contact $contact, string $code, string $handle)
    {
        $this->contact = $contact;
        $this->code = $code;
        $this->handle = $handle;
    }

    /**
     * @throws \BeyondCode\Vouchers\Exceptions\VoucherAlreadyRedeemed
     * @throws \BeyondCode\Vouchers\Exceptions\VoucherExpired
     */
    public function handle()
    {
        $this->contact->enlistRole($this->code, $this->handle);
    }
}
