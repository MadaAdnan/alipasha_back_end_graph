<?php

namespace App\Jobs;

use App\Http\Resources\WebHok\ProductResource;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WebhokProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     * @var $products  Product[]
     */
    public function __construct(private $products, private string $url_webhok)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $response = \Http::post($this->url_webhok, [
                'action' =>'create',
                'type' => 'products',
                'data' => ProductResource::collection($this->products)->jsonSerialize(),
            ]);
            if ($response->successful() && $response->json('status') == 'success') {
                \DB::table('products')->whereIn('id', $this->products->pluck('id')->toArray())->update([
                    'is_sync_webhok' => true,
                ]);
                \Log::info("WEB hok {$response->body()}");
            }
        } catch (\Exception $e) {
            \Log::error("Error Web hok {$e->getMessage()}");
        }
    }
}
