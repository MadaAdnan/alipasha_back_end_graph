<?php

namespace App\Jobs;

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
    public function __construct(private  $products,private string $url_webhok)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try
        {
            $response = \Http::post($this->url_webhok, [
                'action' => $this->action,
                'type'=>'products',
                'data' =>$this->products->map(function ($product){
                    return  [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->getPrice(),
                        'image' => $product->getImage(),
                        'images' => $product->getImages(),
                        'expert' => $product->expert,
                        'info' => $product->info,
                        'url' => $product->url,
                        'email' => $product->email,
                        'phone' => "{$product->user?->phone_code}{$product->user?->phone}",
                        'address' => $product->address,
                        'city' => $product->user?->city?->name,
                        'area' => $product->user?->area?->name,
                    ];
                })
            ]);
            if ($response->successful() && $response->json('status') == 'success') {
                \DB::table('products')->whereIn('id',$this->products->pluck('id')->toArray())->update([
                    'is_sync_webhok' => true,
                ]);

            }
        }catch (\Exception $e)
            {
            \Log::error($e->getMessage());
        }
    }
}
