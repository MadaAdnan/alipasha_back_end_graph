<?php

namespace App\Events;

use App\Http\Resources\Setting\SettingResource;
use App\Models\Setting;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChangeSettingEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
private $setting;
    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        $this->setting = Setting::first();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('change-setting'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'update-setting';
    }

    public function broadcastWith(): array
    {
        return ['setting' => new SettingResource($this->setting)];
    }
}
