<?php

namespace App\Traits;

use OTPHP\TOTP;
use App\Jobs\RequestOTP;
use OTPHP\Factory;
use App\Events\{SMSRelayEvents, SMSRelayEvent};
use LBHurtado\Missive\Traits\HasOTP;

trait Verifiable
{
    use HasOTP {
        challenge as protected hasOTPChallenge;
        verify as protected hasOTPVerify;
    }

    protected $expiration = 60;

    /**
     * @param null $notification
     * @return $this
     */
    public function challenge($notification = null)
    {
        $this->hasOTPChallenge();

        RequestOTP::dispatch($this, $this->getTOTP()->now());

        return $this;
    }

    /**
     * @param $otp
     * @return $this
     */
    public function verify($otp)
    {
        if ($this->hasOTPVerify($otp)->verified())
            event(SMSRelayEvents::VERIFIED, new SMSRelayEvent($this));

        return $this;
    }

    public function isVerificationStale()
    {
        return $this->verified_at && $this->verified_at->addSeconds($this->expiration) <= now();
    }

    public function scopeVerified($query)
    {
        return $query->whereDate('verified_at', '=', now()->toDateString());
    }
}
