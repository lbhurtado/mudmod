<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use App\Models\{Contact, Ration};
use App\Events\{SMSRelayEvents, SMSRelayEvent};

trait CanRation
{
    /**
     * @param string $code
     * @param mixed ...$hashtags
     * @return Contact
     */
    public function ration(string $code, ...$hashtags): Contact
    {
        $hashtags = Arr::flatten($hashtags);

        return $this->rationHashTags($hashtags, $code);
    }

    /**
     * @param array $hashtags
     * @param string $code
     * @return $this
     */
    public function rationHashTags(array $hashtags, string $code)
    {
        optional(Ration::where('code', $code)->first(), function (Ration $ration) use ($hashtags) {
            foreach ($hashtags as $tag) {
                $voucher = $ration->createVoucher();
                $voucher->update(['code' => $tag]);
                $voucher->save();
            }
            event(SMSRelayEvents::RATIONED, (new SMSRelayEvent($this))->setHashtags($hashtags));
        });

        return $this;
    }
}
