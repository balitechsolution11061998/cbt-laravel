<?php

// app/Events/PerformanceDataUpdated.php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class PerformanceDataUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public function __construct()
    {

    }


    public function broadcastOn()
    {
        return new Channel('performance-channel');
    }

    public function broadcastAs()
    {
        return 'performance-data-updated';
    }

}
