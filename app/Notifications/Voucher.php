<?php

namespace App\Notifications;

use Illuminate\Contracts\Queue\ShouldQueue;
use LBHurtado\EngageSpark\Notifications\BaseNotification;

class Voucher extends BaseNotification implements ShouldQueue
{
    public function getContent($notifiable)
    {
        return static::getFormattedMessage($notifiable, $this->message);
    }

    public static function getFormattedMessage($notifiable, $message)
    {
        $handle = $notifiable->handle ?? $notifiable->mobile;
        $signature = config('mudmod.signature');

        return trans('mudmod.voucher', compact('handle', 'message', 'signature'));
    }
}
