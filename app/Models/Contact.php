<?php

namespace App\Models;

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
}
