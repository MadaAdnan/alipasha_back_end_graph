<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendGlobalFirebaseNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $title;
    protected $body;
    protected $offset;

    /**
     * Create a new job instance.
     *
     * @param string $title
     * @param string $body
     * @param int $offset
     */
    public function __construct(string $title, string $body, int $offset = 0)
    {
        $this->title = $title;
        $this->body = $body;
        $this->offset = $offset;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get a batch of 500 users with device tokens
        $users = User::whereNotNull('device_token')
            ->offset($this->offset)
            ->limit(500)
            ->get();

        if ($users->isEmpty()) {
            // No more users to process
            return;
        }

        // Extract device tokens
        $tokens = $users->pluck('device_token')->toArray();

        // Dispatch notification job for this batch
        SendFirebaseNotificationJob::dispatch($tokens, [
            'title' => $this->title,
            'body' => $this->body,
        ]);

        // Check if there are more users to process
        if ($users->count() == 500) {
            // Dispatch another job for the next batch with a 10-second delay
            SendGlobalFirebaseNotificationJob::dispatch($this->title, $this->body, $this->offset + 500)
                ->delay(now()->addSeconds(10));
        }
    }
}