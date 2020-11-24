<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use LBHurtado\Missive\Models\Contact as BaseContact;

class Contact extends BaseContact
{
    use HasRoles;

    protected $guard_name = 'web';

//    protected $appends = array('email');
}
