<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Spatie\Permission\Traits\HasRoles;
use LBHurtado\EngageSpark\Traits\HasEngageSpark;
use LBHurtado\Missive\Models\Contact as BaseContact;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\{CanRedeemVouchers, CanAllocate, CanSegregateHashtags, HasEmail};

class Contact extends BaseContact
{
    use HasFactory, HasRoles, CanRedeemVouchers, HasEngageSpark, CanAllocate, CanSegregateHashtags, HasEmail;

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
     * @param int $amount
     * @param mixed ...$hashtags
     * @return Contact
     */
    public function catch(int $amount, ...$hashtags): Contact
    {
        $hashtags = Arr::flatten($hashtags);

        return $this->catchHashtags($hashtags, $amount);
    }
}
