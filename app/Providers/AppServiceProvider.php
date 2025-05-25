<?php

namespace App\Providers;

use App\GraphQL\Directives\SearchDirectiveDirective;
use App\Models\Cart;
use App\Services\ProductRecommendationService;
use App\Services\ProductViewTrackingService;
use GraphQL\Type\Definition\Directive;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register recommendation services as singletons for better performance
        $this->app->singleton(ProductRecommendationService::class);
        $this->app->singleton(ProductViewTrackingService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {


    }
}
