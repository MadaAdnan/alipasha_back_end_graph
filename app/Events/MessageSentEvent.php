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
     $users=User::whereHas('communities',fn($query)=>$query->where('communities.id',$this->message->community_id))->pluck('id')->toArray();
     foreach ($users as $user){
         $channels[]=new PrivateChannel('message.' . $this->message->community_id.'.'.$user);
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
