<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use LBHurtado\Missive\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hashtag extends Model
{
    use HasFactory, HasSchemalessAttributes;

    protected $fillable = [
        'tag',
        'extra_attributes'
    ];

    protected $appends = array('amount');

    public $casts = [
        'extra_attributes' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * @return array|\ArrayAccess|mixed
     */
    public function getAmountAttribute()
    {
        return Arr::get($this->extra_attributes, 'amount');
    }

    /**
     * @param $value
     */
    public function setAmountAttribute($value)
    {
        Arr::set($this->extra_attributes, 'amount', $value);
    }

    /**
     * @param string $amount
     * @return $this
     */
    public function setAmount(string $amount)
    {
        $this->amount = amount;
        $this->save();

        return $this;
    }
}
