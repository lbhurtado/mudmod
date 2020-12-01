<?php

namespace App\Models;

use Spatie\Permission\Models\Role as Model;
use BeyondCode\Vouchers\Traits\HasVouchers;

class Role extends Model
{
    use HasVouchers;
}
