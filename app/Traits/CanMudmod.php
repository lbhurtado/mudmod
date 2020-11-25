<?php

namespace App\Traits;

use App\Models\Mudmod;

trait CanMudmod
{
    /**
     * @return mixed
     */
    public function mudmods() {
        return $this->hasMany(Mudmod::class);
    }

    /**
     * @param string $key
     * @param float $amount
     * @param int|null $minutes
     * @return Mudmod
     */
    public function magpamudmod(string $key, float $amount, int $minutes = null) {
        $expires_at = $this->getExpiresAt($minutes);
        $mudmod = new Mudmod(compact('key', 'amount', 'expires_at'));

        $this->mudmods()->save($mudmod);

        return $mudmod;
    }

    /**
     * @param int|null $minutes
     * @return \Illuminate\Support\Carbon
     */
    private function getExpiresAt(?int $minutes): \Illuminate\Support\Carbon
    {
        return now()->addMinutes($minutes ?: env('DEFAULT_KEY_EXPIRATION', 60));
    }
}
