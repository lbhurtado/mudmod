<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Events\{SMSRelayEvent, SMSRelayEvents};
use App\Notifications\{Enlisted, Rationed, Collected};

class SMSRelayEventSubscriber implements ShouldQueue
{
    use InteractsWithQueue, DispatchesJobs;

    public function onSMSRelayEnlisted(SMSRelayEvent $event)
    {
        tap($event->getContact(), function ($contact) use ($event) {
            $contact->notify(new Enlisted($event->getVoucher())); //TODO: Create Notification
        });
    }

    public function onSMSRelayRationed(SMSRelayEvent $event)
    {
        tap($event->getContact(), function ($contact) use ($event) {
            $contact->notify(new Rationed($event->getTags()));
        });
    }

    public function onSMSRelayCollected(SMSRelayEvent $event)
    {
        tap($event->getContact(), function ($contact) use ($event) {
            tap($event->getVoucher(), function ($voucher) use ($contact) {
                tap($contact)->consume($voucher->model)
                    ->notify(new Collected($voucher));
            });
        });
    }

    public function subscribe($events)
    {
        $events->listen(
            SMSRelayEvents::ENLISTED,
            SMSRelayEventSubscriber::class.'@onSMSRelayEnlisted'
        );

        $events->listen(
            SMSRelayEvents::RATIONED,
            SMSRelayEventSubscriber::class.'@onSMSRelayRationed'
        );

        $events->listen(
            SMSRelayEvents::COLLECTED,
            SMSRelayEventSubscriber::class.'@onSMSRelayCollected'
        );
    }
}
