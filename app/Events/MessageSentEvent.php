<?php

namespace App\Events;

use App\Http\Resources\Community\MessageResource;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSentEvent implements ShouldBroadcastNow
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
        // جمع القنوات في مصفوفة
        $channels = [];

        // جلب المستخدمين الذين ليسوا مرسلين للرسالة
        $users = $this->message->community->users()
            ->where('users.id', '!=', $this->message->user_id)
            ->pluck('id');
        return [
            new PrivateChannel('message.' . $this->message->community_id),
        ];
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
