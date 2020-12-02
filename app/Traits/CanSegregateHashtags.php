<?php

namespace App\Traits;

use App\Models\Hashtag;
use App\Events\{SMSRelayEvents, SMSRelayEvent};

trait CanSegregateHashtags
{
    public function hashtags()
    {
        return $this->hasMany(Hashtag::class);
    }

    public function catchHashtags(array $hashtags, int $amount = 0)
    {
        $tags = [];
        foreach ($hashtags as $tag) {
            $tags[] = ['tag' => $tag, 'extra_attributes' => ['amount' => $amount]];
        }
        $this->hashtags()->createMany($tags);
        event(SMSRelayEvents::LISTENED, (new SMSRelayEvent($this))->setHashtags($hashtags));

        return $this;
    }

//    public function uncatchHashtags(array $hashtags)
//    {
//        foreach ($hashtags as $hashtag) {
//            $this->hashtags()->where('tag', $hashtag)->delete();
//        }
//        event(SMSRelayEvents::UNLISTENED, (new SMSRelayEvent($this))->setHashtags($hashtags));
//
//        return $this;
//    }
}
