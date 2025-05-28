<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Qoraiche\Peak\Events\Billing\SubscriptionCreated;

class LogUserSubscribedEvent
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SubscriptionCreated $event): void
    {
        activity()->causedBy($event->billable)
            ->event('subscribed')
            ->log(__('User has subscribed'));
    }
}
