<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\{Contact, Role};
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
    public $name;

    /**
     * RedeemCode constructor.
     * @param Contact $contact
     * @param string $code
     * @param string $name
     */
    public function __construct(Contact $contact, string $code, string $name)
    {
        $this->contact = $contact;
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->contact->syncRoles($this->getRole());
        $this->contact->update(['handle' => $this->name]);
        $this->contact->save();
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
