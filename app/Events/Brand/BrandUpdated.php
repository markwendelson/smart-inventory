<?php

namespace App\Events\Brand;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BrandUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $brand;

    public function __construct($brand)
    {
        $this->brand = $brand;
    }

    public function broadcastOn()
    {
        return new Channel('brandUpdated');
    }

    public function broadcastAs()
    {
        return 'brand-updated';
    }
}
