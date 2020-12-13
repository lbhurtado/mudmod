<?php

namespace App\Http\Controllers;

use App\Models\Contact;

class TransactController extends Controller
{
    /**
     * @param string $mobile
     * @param string $action
     * @param int $amount
     * @return Contact|null
     */
    public function transfer(string $mobile, string $action, int $amount)
    {
        $contact = Contact::bearing($mobile);
        $contact->{$action}($amount);

        return $contact;
    }
}
