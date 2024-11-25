<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\UserNotification;
use App\Service\SendNotifyHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $data;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $data)
    {
        //
        $this->user = $user;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if ($this->user instanceof User) {
                $this->user->notify(new UserNotification($this->data));
              // SendNotifyHelper::sendNotify($this->user ,$this->data);
            } else {
                \Notification::send($this->user, new UserNotification($this->data));
            }
        } catch (\Exception | \Error $e) {

        }


    }
}
