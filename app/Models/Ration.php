<?php

namespace App\Models;

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
}
