<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mudmod extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'amount',
        'expires_at'
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
