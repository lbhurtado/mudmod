<?php

namespace App\Jobs;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;


class PlaceBet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Contact */
    public $contact;

    public $date;

    public $game;

    public $hand;

    public $amount;

    public function __construct(Contact $contact, $date, $game, $hand, int $amount)
    {
        $this->contact = $contact;
        $this->date = $date;
        $this->game = $game;
        $this->hand = $hand;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->contact->placeBet($this->date, $this->game, $this->hand, $this->amount);
    }
}
