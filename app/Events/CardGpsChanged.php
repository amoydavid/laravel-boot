<?php

namespace App\Events;

use App\Models\NameCard;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CardGpsChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public NameCard $card;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(NameCard $card)
    {
        $this->card = $card->withoutRelations();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
