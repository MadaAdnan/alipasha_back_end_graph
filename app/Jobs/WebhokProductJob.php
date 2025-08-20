<?php

namespace App\Jobs;

use App\Http\Resources\WebHok\ProductResource;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;

class WebhokProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private Product $product, private $action = 'create')
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (\Str::isUrl($this->product->user?->url_webhok)) {
            $url=$this->product->user?->url_webhok;
            $domain= parse_url($url, PHP_URL_HOST);
            \Log::error("START");
           try{
               $response = \Http::post($url, [
                   'action' => $this->action,
                   'type'=>'product',
                   'domain'=>$domain,
                   'data' => new ProductResource($this->product),
               ]);
               if ($response->successful() && $response->json('status') == 'success') {
                   \DB::table('products')->where('id',$this->product->id)->update([
                       'is_sync_webhok' => true,
                   ]);

               }
               \Log::error("End => ".$response->body());
           }catch (\Exception $e){

               \Log::error("Error WebHok ".$e->getMessage());
           }
        }
    }
}
