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
        Ration::create(['code' => 'MIN', 'amount' => 100]);
        Ration::create(['code' => 'LOW', 'amount' => 200]);
        Ration::create(['code' => 'MID', 'amount' => 300]);
        Ration::create(['code' => 'GEN', 'amount' => 400]); //generous
        Ration::create(['code' => 'MAX', 'amount' => 500]);
    }
}
