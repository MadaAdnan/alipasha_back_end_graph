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


    /**
     * Create a new job instance.
     *
     * @param string $title
     * @param string $body
     */
    public function __construct(string $title, string $body)
    {
        $this->title = $title;
        $this->body = $body;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Process users in chunks of 500 with 10 second delays between each chunk
        User::whereNotNull('device_token')->chunk(200, function ($users, $index) {
            // Extract device tokens
            $tokens = $users->pluck('device_token')->toArray();
            $tokens = collect($tokens ?? [])
                ->filter(fn($t) => !empty($t) && \Str::lower($t)!='null');
            // Dispatch notification job for this chunk with delay based on chunk index
            SendFirebaseNotificationJob::dispatch($tokens->toArray(), [
                'title' => $this->title,
                'body' => $this->body,
            ])->delay(now()->addSeconds($index * 10));
        });
    }
}
