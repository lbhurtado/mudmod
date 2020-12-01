<?php

namespace App\CommandBus\Commands;

use LBHurtado\Missive\Models\SMS;
use App\Contracts\CommandTicketable;

class RelayCommand extends BaseCommand implements CommandTicketable
{
    /** @var SMS */
    public $sms;

    /**
     * RelayCommand constructor.
     * @param SMS $sms
     */
    public function __construct(SMS $sms)
    {
        $this->sms = $sms;
    }

    /**
     * @return SMS
     */
    function getSMS()
    {
        return $this->sms;
    }

//    public function getTicket()
//    {
//        return $this->sms->origin->tickets->last();
//    }


}
