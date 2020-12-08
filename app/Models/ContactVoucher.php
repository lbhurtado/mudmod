<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ContactVoucher extends Pivot
{
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
