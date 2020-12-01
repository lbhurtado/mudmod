<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use BeyondCode\Vouchers\Facades\Vouchers;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = config('mudmod.vouchers');

        foreach ($records as $name => $qty) {
            optional(Role::where('name', $name)->first(), function ($role) use ($qty) {
                Vouchers::create($role, $qty);
            });
        }
    }
}
