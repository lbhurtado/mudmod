<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\{CanSegregateHashtags, HasEmail};
use LBHurtado\EngageSpark\Traits\HasEngageSpark;
use LBHurtado\Missive\Models\Contact as BaseContact;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends BaseContact
{
    use HasFactory, HasRoles, HasEngageSpark, CanSegregateHashtags, HasEmail;

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
     * @param mixed ...$hashtags
     * @return Contact
     */
    public function catch(...$hashtags): Contact
    {
        $hashtags = Arr::flatten($hashtags);

        return $this->catchHashtags($hashtags);
    }
}
