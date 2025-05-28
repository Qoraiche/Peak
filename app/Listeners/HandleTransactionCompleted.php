<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\LicensePurchasedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Paddle\Events\TransactionCompleted;

class HandleTransactionCompleted
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
    public function handle(TransactionCompleted $event): void
    {
        /** @var User $billable */
        $billable = $event->billable;

        $billable->notify(new LicensePurchasedNotification);
    }
}
