<?php

namespace App\Listeners;

use App\Events\CustomerCheckedIn;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogCustomerActivity
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
    public function handle(CustomerCheckedIn $event): void
    {
        //
    }
}
