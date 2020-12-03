<?php

namespace Database\Seeders;

use App\Models\Ration;
use Illuminate\Database\Seeder;

class RationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        parse_str(config('mudmod.rations.default'), $rations);
        foreach ($rations as $code => $amount) {
            Ration::create(['code' => $code, 'amount' => $amount]);
        }
    }
}
