<?php

namespace App\Events;

use App\Models\Purchase;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ComponentPurchased
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $purchase;
    public $component;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
        $this->user = $purchase->user;
        
        if ($purchase->purchasable_type === 'App\Models\Component') {
            $this->component = $purchase->purchasable;
        } else {
            $this->component = null;
        }
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->user->id),
        ];
    }
}
