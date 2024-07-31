<?php

namespace App\Listeners;

use App\Events\OrderStoredEvent;
use App\Mail\OrderStored;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderStoredEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  OrderStoredEvent  $event
     * @return void
     */
    public function handle(OrderStoredEvent $event)
    {
        // Send email to supplier

        Mail::to($event->order['supplier_email'])->send(new OrderStored($event->order));
    }
}
