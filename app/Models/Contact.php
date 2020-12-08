<?php

namespace App\Models;

use DB;
use Balance;
use App\Models\Ration;
use BeyondCode\Vouchers\Models\Voucher;
use Spatie\Permission\Traits\HasRoles;
use LBHurtado\EngageSpark\Traits\HasEngageSpark;
use LBHurtado\Missive\Models\Contact as BaseContact;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\{CanRedeemVouchers, CanRationHashTags, HasEmail};

class Contact extends BaseContact
{
    use HasFactory, HasRoles, CanRedeemVouchers, HasEngageSpark, CanRationHashTags, HasEmail;

    protected $guard_name = 'web';

    protected $appends = array('email');

    /**
     * @param string $mobile
     * @return Contact|null
     */
    public static function bearing(string $mobile):? Contact //TODO change this to by
    {
        $phone = phone($mobile, config('mudmod.country'))
            ->formatE164();

        return static::where('mobile', $phone)->first();
    }

    /**
     * @return string
     */
    public static function nameRegex(): string
    {
        return ".*";
    }

    public function increase(int $amount)
    {
        Balance::increase(
            [
                'contact_id' => $this->id,
                'type' => 'virtual-money',
            ],
            $amount,
            [
                'mobile' => $this->mobile,
            ]
        );

        return $this;
    }

    public function getBalanceAttribute()
    {
        $contact_id = $this->id;
        $type = 'virtual-money';

        return Balance::calculateBalance(compact('contact_id', 'type'));
    }

    public function rations()
    {
        return  $this->vouchers()->where('model_type', Ration::class);
    }

    public function getRationsAttribute()
    {
        return  $this->rations()->get();
    }

    public function consume(Ration $ration)
    {
        $exception = DB::transaction(function() use ($ration) {
            $this->increase($ration->getAmount());
            tap($this->rations()->where('model_id', $ration->getId())->first()->pivot, function (ContactVoucher $pivot) {
                $pivot->setAttribute('increased_at', now());
                $pivot->save();
            });

        });

        return is_null($exception) ? true : false;
    }
}
