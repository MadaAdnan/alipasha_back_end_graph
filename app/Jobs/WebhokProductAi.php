<?php

namespace App\Jobs;

use App\Enums\ProductActiveEnum;
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
        \Log::warning("OK AI TRUE START");
        $product=new ProductResource($this->product);
        try{
            $res = \Http::post('http://85.215.154.88:5000/calculate-weight',$product);
            if ($res->successful() && (double)$res->json('ratio') > 0) {
                info($res->body());
                $this->product->update([
                    'weight' => $res->json('weight'),
                    'power'=>$res->json('ratio'),
                    'active'=> $res->json('status') == 'success' ? ProductActiveEnum::ACTIVE->value : ($res->json('status') == 'block' ? ProductActiveEnum::BLOCK->value : ProductActiveEnum::PENDING->value),
                    'block_msg' => $res->json('description')
                ]);
            }
        }catch (\Throwable $exception){
            \Log::error("AI ERROR: {$exception->getMessage()}");
        }

    }
}
