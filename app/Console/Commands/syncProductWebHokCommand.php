<?php

namespace App\Console\Commands;

use App\Jobs\WebhokProductsJob;
use App\Jobs\WebhokUserJob;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;

class syncProductWebHokCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ysnc products with store  webhok';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::whereNotNull('url_webhok')
            ->whereHas('products', fn($q) => $q->where('is_sync_webhok', false))
            ->each(function ($user) {
                Product::where('user_id', $user->id)
                    ->where('is_sync_webhok', false)
                    ->chunk(30, function ($products) use ($user) {
                        dispatch(new WebhokProductsJob($products, $user->url_webhok));
                    });

                if ($user->is_sync_webhok == false) {
                    dispatch(new WebhokUserJob($user));
                }
            });
      /*  $users = User::whereNotNull('url_webhok')->whereHas('products', fn($query) => $query->where('products.is_sync_webhok', false))
            ->with([
                'products' => fn($query) => $query->where('products.is_sync_webhok', false),
            ])->get();
        foreach ($users as $user) {

            $user->products->chunk(50)->each(function ($chunkedProducts) use ($user) {
                // نمرر كل دفعة إلى Job
                \Log::error('Products');
                \Log::error($chunkedProducts->count());
                $job = new WebhokProductsJob($chunkedProducts, $user->url_webhok);
                dispatch($job);
            });*/




       //   $job = new WebhokProductsJob($user->products, $user->url_webhok);
          //  dispatch($job);
//            if ($user->is_sync_webhok == false) {
//                $job2 = new WebhokUserJob($user);
//                dispatch($job2);
//            }
//        }
    }
}
