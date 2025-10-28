<?php

namespace App\Jobs;

use App\Http\Resources\WebHok\ProductResource;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WebhokProductAi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Product $product;

    /**
     * Create a new job instance.
     */
    public function __construct($product)
    {
        //
        $this->product = $product;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $product= ProductResource::toArray($this->product);
        $res = \Http::post('http://85.215.154.88:5000/calculate-weight',$product);
        if ($res->successful() && (double)$res->json('total_weight') > 0) {

        }
    }
}
