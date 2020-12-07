<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->manual_seed();
    }

    protected function manual_seed()
    {
        $records = [
            ['09182222222', 'agent'],
            ['09183333333', 'cashier'],
            ['09184444444', 'subscriber'],
            ['09185555555', 'subscriber'],
            ['09186666666', 'subscriber'],
            ['09187777777', 'subscriber'],
        ];

        foreach ($records as $record) {
            $mobile = $record[0];
            $contact = Contact::create(compact('mobile'))->assignRole($record[1]);
            $contact->save();
        }
    }
}
