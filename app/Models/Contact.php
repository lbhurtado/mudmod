<?php

namespace App\Models;

use App\Traits\CanMudmod;
use Spatie\Permission\Traits\HasRoles;
use LBHurtado\EngageSpark\Traits\HasEngageSpark;
use LBHurtado\Missive\Models\Contact as BaseContact;

class Contact extends BaseContact
{
    use HasRoles, HasEngageSpark, CanMudmod;

    protected $guard_name = 'web';
}
