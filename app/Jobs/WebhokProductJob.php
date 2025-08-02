<?php

namespace App\Jobs;

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
           try{
               $response = \Http::post($this->product->user?->url_webhok, [
                   'action' => $this->action,
                   'type'=>'product',
                   'data' => [
                       'id' => $this->product->id,
                       'name' => $this->product->name,
                       'price' => $this->product->getPrice(),
                       'image' => $this->product->getImage(),
                       'images' => $this->product->getImages(),
                       'expert' => $this->product->expert,
                       'info' => $this->product->info,
                       'url' => $this->product->url,
                       'email' => $this->product->email,
                       'phone' => "{$this->product->user?->phone_code}" . "{$this->product->user?->phone}",
                       'address' => $this->product->address,
                       'city' => $this->product->user?->city?->name,
                       'area' => $this->product->user?->area?->name,
                   ]
               ]);
               if ($response->successful() && $response->json('status') == 'success') {
                   \DB::table('products')->where('id',$this->product->id)->update([
                       'is_sync_webhok' => true,
                   ]);

               }
           }catch (\Exception $e){
               \Log::error($e->getMessage());
           }
        }
    }
}
