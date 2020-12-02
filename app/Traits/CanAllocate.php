<?php

namespace App\Traits;

use App\Models\Contact;
use App\Models\Allocation;
use Illuminate\Support\Arr;
use App\Events\{SMSRelayEvents, SMSRelayEvent};

trait CanAllocate
{
    /**
     * @return mixed
     */
    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }

    /**
     * @param int $amount
     * @param mixed ...$hashtags
     * @return Contact
     */
    public function allocate(int $amount, ...$hashtags): Contact
    {
        $hashtags = Arr::flatten($hashtags);

        return $this->allocateHashTags($hashtags, $amount);
    }

    /**
     * @param array $hashtags
     * @param int $amount
     * @return $this
     */
    public function allocateHashTags(array $hashtags, int $amount = 0)
    {
        $tags = [];
        foreach ($hashtags as $tag) {
            $tags[] = ['tag' => $tag, 'amount' => $amount];
        }
        $this->allocations()->createMany($tags);
        event(SMSRelayEvents::ALLOCATED, (new SMSRelayEvent($this))->setHashtags($hashtags));

        return $this;
    }
}
