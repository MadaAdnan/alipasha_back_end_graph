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
            $url=$this->user?->url_webhok;
            $domain= parse_url($url, PHP_URL_HOST);

            $response = \Http::post($url, [
                'action' => 'update',
                'type' => 'user',
                'domain'=>$domain,
                'data' => (new UserResource($this->user))->jsonSerialize(),
            ]);
            if ($response->successful() && $response->json('status') == 'success') {
                \DB::table('users')->where('id', $this->user->id)->update([
                    'is_sync_webhok' => true,
                ]);
            }
            \Log::error("End => ".$response->body());
        } catch (\Exception|\Error $e) {
            \Log::error($e->getMessage());
        }
    }
}
