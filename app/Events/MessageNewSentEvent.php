<?php

namespace App\Events;

use App\Http\Resources\Community\MessageResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageNewSentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Message $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {

        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {

        $channels = [];
        foreach ($this->message->community->users as $user) {
            if ($user->id !== $this->message->user_id) {
                $channels[] = new PrivateChannel("user.{$user->id}");
            }
        }
        return $channels;
    }


    public function broadcastAs(): string
    {
        return 'message.create';
    }

    public function broadcastWith(): array
    {
       // $this->message->load(['user', 'community', 'media']);

        return ['message' => new MessageResource($this->message)];
    }

}
