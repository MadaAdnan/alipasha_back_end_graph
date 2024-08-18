<?php

namespace App\Events;

use App\Http\Resources\Community\CommunityResource;
use App\Models\Community;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateCommunityEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Community $community;

    /**
     * Create a new event instance.
     */
    public function __construct(Community $community)
    {
        //
        $this->community = $community;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('community.' . $this->community->seller_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'community.create';
    }

    public function broadcastWith(): array
    {
        $this->community->load('user', 'seller');

        return ['community' => new CommunityResource($this->community)];
    }
}
