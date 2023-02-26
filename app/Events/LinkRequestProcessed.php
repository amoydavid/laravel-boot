<?php

namespace App\Events;

use App\Models\LinkRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LinkRequestProcessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public LinkRequest $linkRequest;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(LinkRequest $linkRequest)
    {
        $this->linkRequest = $linkRequest->withoutRelations();
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
