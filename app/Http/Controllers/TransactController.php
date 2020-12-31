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

    /**
     * @param string $mobile
     * @param string $otp
     * @param int $amount
     * @return Contact|null
     */
    public function debit(string $mobile, string $otp, int $amount)
    {
//        $contact = Contact::bearing($mobile);
//        if (! $contact)
//            return response('Error: mobile number not found!', 500);

//        $contact->verify($otp);

//        if (! $contact->verified())
//            return response('Error: mobile number not verified!', 500);

//        if ($contact->balance < $amount)
//            $amount = $contact->balance;
//
//        $contact->debit($amount);
        $mobile = '+639173011987';
        $amount = 500;
        $message = 'The quick brown fox...';

        return response(json_encode(compact('mobile', 'amount', 'message')), 200)
            ->header('Content-Type', 'text/json');
    }
}
