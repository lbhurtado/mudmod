<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Spatie\Permission\Traits\HasRoles;
use LBHurtado\EngageSpark\Traits\HasEngageSpark;
use LBHurtado\Missive\Models\Contact as BaseContact;
use App\Traits\{CanMudmod, CanSegregateHashtags};

class Contact extends BaseContact
{
    use HasRoles, HasEngageSpark, CanMudmod, CanSegregateHashtags;

    protected $guard_name = 'web';

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
