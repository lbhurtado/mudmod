<?php

namespace Database\Seeders;

use App\Models\Mudmod;
use Illuminate\Database\Seeder;

class MudmodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Mudmod::create(['key' => 'JAWO', 'amount' => 100, 'expires_at' => now()->addMinutes(30)]);
        Mudmod::create(['key' => 'JOLAS', 'amount' => 50]);
    }
}
