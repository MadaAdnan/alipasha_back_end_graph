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
      /*  User::whereNotNull('url_webhok')
            ->whereHas('products', fn($q) => $q->where('is_sync_webhok', false))
            ->each(function ($user) {
                Product::where('user_id', $user->id)
                    ->where('is_sync_webhok', false)
                    ->chunk(30, function ($products) use ($user) {
                        try {
                            dispatch(new WebhokProductsJob($products, $user->url_webhok));

                        } catch (\Throwable $e) {
                            \Log::error("Error dispatching job: " . $e->getMessage());
                        }
                    });

                if ($user->is_sync_webhok == false) {
                    dispatch(new WebhokUserJob($user));
                }
            });*/
        User::whereNotNull('url_webhok')
            ->whereHas('products', fn($q) => $q->where('is_sync_webhok', false))
            ->each(function ($user) {
                Product::where('user_id', $user->id)
                    ->where('is_sync_webhok', false)
                    ->orderBy('id')
                    ->lazyById(30)
                    ->chunk(30)->each( function ($products) use ($user) {
                        try {
                            dispatch(new WebhokProductsJob($products, $user->url_webhok));

                        } catch (\Throwable $e) {
                            \Log::error("Error dispatching job Sync: " . $e->getMessage());
                        }
                    });
                if ($user->is_sync_webhok == false) {
                    dispatch(new WebhokUserJob($user));
                }

            });
    }
}
