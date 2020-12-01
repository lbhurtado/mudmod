<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AirtimeSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(VoucherSeeder::class);
//        $this->call(ContactSeeder::class);
    }
}
