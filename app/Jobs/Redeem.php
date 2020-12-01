<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\{Contact, Role};
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Redeem implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Contact */
    public $contact;

    /** @var string */
    public $code;

    /** @var string */
    public $email;

    /**
     * RedeemCode constructor.
     * @param Contact $contact
     * @param string $code
     * @param string $email
     */
    public function __construct(Contact $contact, string $code, string $email)
    {
        $this->contact = $contact;
        $this->code = $code;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->contact
            ->syncRoles($this->getRole())
            ->setEmail($this->email)
        ;
    }

    protected function getRole(): Role
    {
        return $this->getVoucher()->model;
    }

    protected function getVoucher(): \BeyondCode\Vouchers\Models\Voucher
    {
        return $this->contact->redeemCode($this->code);
    }
}
