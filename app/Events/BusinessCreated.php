<?php

namespace App\Events;

use App\Models\Business;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BusinessCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Business $business;

    public function __construct(Business $business)
    {
        $this->business = $business;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
   
}
