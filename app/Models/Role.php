<?php

namespace App\Models;

use BeyondCode\Vouchers\Models\Voucher;
use Spatie\Permission\Models\Role as Model;
use BeyondCode\Vouchers\Traits\HasVouchers;

class Role extends Model
{
    use HasVouchers;

    /**
     * @return string
     */
    static public function codeRegex() : string
    {
        return implode('|', Voucher::where('model_type', Role::class)->get()->pluck('code')->toArray());
    }
}
