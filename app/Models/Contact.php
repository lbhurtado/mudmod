<?php

namespace App\Models;

use DB;
use Balance;
use Spatie\Permission\Traits\HasRoles;
use LBHurtado\EngageSpark\Traits\HasEngageSpark;
use LBHurtado\Missive\Models\Contact as BaseContact;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\{CanRedeemVouchers, CanRationHashTags, HasEmail, Verifiable};

class Contact extends BaseContact
{
    use HasFactory, HasRoles, CanRedeemVouchers, HasEngageSpark, CanRationHashTags, HasEmail, Verifiable;

    protected $guard_name = 'web';

    protected $appends = array('email', 'balance');

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

    /**
     * @param int $amount
     * @return $this
     */
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

    public function credit(int $amount)
    {
        return $this->increase($amount);
    }

    public function debit(int $amount)
    {
        return $this->increase($amount * -1);
    }

    /**
     * @return float
     */
    public function getBalanceAttribute():float
    {
        $contact_id = $this->id;
        $type = 'virtual-money';//TODO: put this somewhere appropriate

        return Balance::calculateBalance(compact('contact_id', 'type'));
    }

    /**
     * @return mixed
     */
    public function rations()
    {
        return  $this->vouchers()->where('model_type', Ration::class);
    }

    /**
     * @return mixed
     */
    public function getRationsAttribute()
    {
        return  $this->rations()->get();
    }

    /**
     * @param Ration $ration
     * @return bool
     */
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
