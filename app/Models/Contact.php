<?php

namespace App\Models;

use Illuminate\Support\Arr;
use App\Traits\{CanSegregateHashtags};
use Spatie\Permission\Traits\HasRoles;
use LBHurtado\EngageSpark\Traits\HasEngageSpark;
use LBHurtado\Missive\Models\Contact as BaseContact;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends BaseContact
{
    use HasFactory, HasRoles, HasEngageSpark, CanSegregateHashtags;

    protected $guard_name = 'web';

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
