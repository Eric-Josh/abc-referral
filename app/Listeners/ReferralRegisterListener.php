<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Notification;
use App\Notifications\ReferralNotification;

class ReferralRegisterListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $referrer = $event->user->referrer ?? null;
        if ($referrer !== null) {
            Notification::send($referrer, new ReferralNotification($event->user));
        }
    }
}
