<?php

namespace Database\Seeders;

use Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::forget('PIN');
        Setting::set('PIN', env('PIN', 1234));
        Setting::save();
    }
}
