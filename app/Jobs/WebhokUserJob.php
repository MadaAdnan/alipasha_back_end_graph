<?php

namespace App\Jobs;

use App\Http\Resources\WebHok\UserResource;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WebhokUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly User $user)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            \Log::info("SYNC USER");
            $response = \Http::asForm()->post($this->user?->url_webhok, [
                'action' => 'update',
                'type' => 'user',
                'data' => new UserResource($this->user)
            ]);
            if ($response->successful() && $response->json('status') == 'success') {
                \DB::table('users')->where('id', $this->user->id)->update([
                    'is_sync_webhok' => true,
                ]);
            }
        } catch (\Exception|\Error $e) {
            \Log::error($e->getMessage());
        }
    }
}
