<?php

namespace App\Http\Controllers;

use App\Jobs\PlaceBet;
use App\Models\Contact;
use Symfony\Component\HttpFoundation\Response;

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
        $contact = Contact::bearing($mobile);
        if (! $contact)
            return response('Error: mobile number not found!', Response::HTTP_INTERNAL_SERVER_ERROR);

        $contact->verify($otp);

        if (! $contact->verified())
            return response('Error: mobile number not verified!', Response::HTTP_INTERNAL_SERVER_ERROR);

        if ($contact->balance < $amount)
            $amount = $contact->balance;

        $contact->debit($amount);
        $message = 'The quick brown fox...';

        return response(json_encode(compact('mobile', 'amount', 'message')), Response::HTTP_OK)
            ->header('Content-Type', 'text/json');
    }

    public function getBet(string $mobile)
    {
        $contact = Contact::bearing($mobile);
        if (! $contact)
            return response('Error: mobile number not found!', Response::HTTP_INTERNAL_SERVER_ERROR);

        return response(json_encode($contact->bet), Response::HTTP_OK)
            ->header('Content-Type', 'text/json');
    }

    public function placeBet(string $mobile, string $date, int $game, string $hand, int $amount)
    {
        $contact = Contact::bearing($mobile);
        if (! $contact)
            return response('Error: mobile number not found!', Response::HTTP_INTERNAL_SERVER_ERROR);

        $this->dispatch(new PlaceBet($contact, $date, $game, $hand, $amount));

        return response(json_encode(compact('mobile', 'date', 'game', 'hand', 'amount')), Response::HTTP_OK)
            ->header('Content-Type', 'text/json');
    }
}
