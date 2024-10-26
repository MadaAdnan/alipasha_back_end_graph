<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFirebaseNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $ids;
    private $data;

    /**
     * Create a new job instance.
     */
    public function __construct($ids,$data)
    {
        //
        $this->ids = $ids;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
//       new SendFirebaseNotification($this->ids,$this->data);
        info('Iam Jn JOB');
    }
}
