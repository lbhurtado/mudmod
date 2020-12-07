<?php

namespace App\Models;

use BeyondCode\Vouchers\Models\Voucher;
use Illuminate\Database\Eloquent\Model;
use BeyondCode\Vouchers\Traits\HasVouchers;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ration extends Model
{
    use HasFactory, HasVouchers;

    protected $fillable = [
        'code',
        'amount',
        'message'
    ];

    /**
     * @return string
     */
    static public function tagsRegex() : string
    {
        return implode('|', Voucher::where('model_type', Ration::class)->get()->pluck('code')->toArray());
    }
}
