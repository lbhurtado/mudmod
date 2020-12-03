<?php

namespace App\CommandBus\Handlers;

use App\Models\Contact;
use Twitter\Text\Extractor;
use Illuminate\Support\Arr;
use App\Notifications\Hashtags;
use App\CommandBus\Commands\RelayCommand;
use Illuminate\Support\Facades\Notification;
use App\Events\{SMSRelayEvents, SMSRelayEvent};
use BeyondCode\Vouchers\Exceptions\VoucherAlreadyRedeemed;

class RelayHandler
{
    /**
     * @var Extractor
     */
    protected $extractor;

    /**
     * RelayHandler constructor.
     * @param Extractor $extractor
     */
    public function __construct(Extractor $extractor)
    {
        $this->extractor = $extractor;
    }

    /**
     * @param RelayCommand $command
     */
    public function handle(RelayCommand $command)
    {
        foreach ($this->getHashtags($command) as $hashtag) {
            try {
                $command->sms->origin->redeemCode($hashtag);
            }
            catch (VoucherAlreadyRedeemed $e) {

            }
        };
//        event(SMSRelayEvents::RELAYED, (new SMSRelayEvent($command->sms->origin))->setMessage($command->sms->getMessage()));
    }

    /**
     * @param RelayCommand $command
     * @return array
     */
    protected function getHashtags(RelayCommand $command): array
    {
        $extracted = $this->extractor->extract($command->sms->getMessage());

        return Arr::get($extracted, 'hashtags');
    }

    /**
     * @param string $hashtag
     * @return mixed
     */
    protected function getContacts(string $hashtag)
    {
        return Contact::whereHas('hashtags', function ($query) use ($hashtag) {
            $query->where('tag', $hashtag);
        })->get();
    }
}
