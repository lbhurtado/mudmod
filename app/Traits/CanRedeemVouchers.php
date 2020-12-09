<?php

namespace App\Traits;

use App\Models\Role;
use Vouchers;
use App\Models\ContactVoucher as Pivot;
use BeyondCode\Vouchers\Models\Voucher;
use App\Events\{SMSRelayEvents, SMSRelayEvent};
use BeyondCode\Vouchers\Exceptions\VoucherExpired;
use BeyondCode\Vouchers\Exceptions\VoucherIsInvalid;
use BeyondCode\Vouchers\Exceptions\VoucherAlreadyRedeemed;

trait CanRedeemVouchers
{
    /**
     * @return mixed
     */
    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class)->using(Pivot::class)->withPivot('redeemed_at', 'increased_at');
    }

    /**
     * @param string $code
     * @param string $handle
     * @return $this
     * @throws VoucherAlreadyRedeemed
     * @throws VoucherExpired
     */
    public function enlistRole(string $code, string $handle = null) {
        $voucher = $this->redeemCode($code);
        tap($voucher->model, function (Role $role) use ($handle) {
            $this->syncRoles($role);
            if ($handle) {
                tap($this)->update(compact('handle'))->save();
            }
        });
        event(SMSRelayEvents::ENLISTED, (new SMSRelayEvent($this))->setVoucher($voucher));

        return $this;
    }

    public function collectRation(string $code, string $handle) {
        $voucher = null;
        try {
            $voucher = $this->redeemCode($code);
            event(SMSRelayEvents::COLLECTED, (new SMSRelayEvent($this))->setVoucher($voucher));
        }
        catch (VoucherAlreadyRedeemed $e) {

        }
        $this->setAttribute('handle', $handle)->save();

        return $voucher;
    }

    /**
     * @param string $code
     * @return mixed
     * @throws VoucherAlreadyRedeemed
     * @throws VoucherExpired
     */
    protected function redeemCode(string $code)
    {
        $voucher = Vouchers::check($code);

        if ($voucher->users()->wherePivot('contact_id', $this->attributes['id'])->exists()) {
            throw VoucherAlreadyRedeemed::create($voucher);
        }
        if ($voucher->isExpired()) {
            throw VoucherExpired::create($voucher);
        }

        $this->vouchers()->attach($voucher, [
            'redeemed_at' => now()
        ]);

        return $voucher;
    }
}
