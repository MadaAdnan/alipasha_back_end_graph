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
        User::whereNotNull('device_token')->chunk(300, function ($users, $index) {
            // Extract device tokens
            $tokens = $users->pluck('device_token')->toArray();
            $tokens = collect($tokens ?? [])
                ->filter(fn($t) => is_string($t))                         // لازم سترنغ
                ->map(fn($t) => trim($t))
                ->filter(fn($t) => strtolower($t) !== 'null')             // شيل "null"
                ->filter(fn($t) => preg_match('/^[A-Za-z0-9:_-]{100,200}$/', $t));
            // Dispatch notification job for this chunk with delay based on chunk index
            SendFirebaseNotificationJob::dispatch($tokens, [
                'title' => $this->title,
                'body' => $this->body,
            ])->delay(now()->addSeconds($index * 10));
        });
    }
}
