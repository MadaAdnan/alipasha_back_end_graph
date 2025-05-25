<?php declare(strict_types=1);

namespace App\GraphQL\Queries\HomeQuery;

use App\Enums\CategoryTypeEnum;
use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Product;
use App\Services\ProductRecommendationService;
use App\Services\ProductViewTrackingService;
use Illuminate\Support\Collection;

final class HobbiesProduct
{
    public function __construct(
        private ProductRecommendationService $recommendationService,
        private ProductViewTrackingService $viewTrackingService
    ) {}

    /**
     * @param null $_
     * @param array{page?: int, perPage?: int} $args
     */
    public function __invoke($_, array $args): Collection
    {
        $page = $args['page'] ?? 1;
        $perPage = $args['perPage'] ?? 50;
        $user = auth()->user();

        // Get smart product recommendations
        $products = $this->getSmartRecommendations($user, $perPage);

        // Track views for analytics
        $this->trackProductViews($products);

        // Return the products collection directly
        return $products;
    }

    /**
     * Get smart product recommendations based on user behavior
     */
    private function getSmartRecommendations($user, int $perPage): Collection
    {
        if ($user) {
            return $this->getAuthenticatedUserRecommendations($user, $perPage);
        }

        return $this->getGuestUserRecommendations($perPage);
    }

    /**
     * Get recommendations for authenticated users
     */
    private function getAuthenticatedUserRecommendations($user, int $perPage): Collection
    {
        // Calculate distribution percentages
        $specialCount = (int) floor($perPage * 0.25);      // 25% special products
        $interestCount = (int) floor($perPage * 0.45);     // 45% interest-based
        $trendingCount = (int) floor($perPage * 0.20);     // 20% trending
        $remainingCount = $perPage - ($specialCount + $interestCount + $trendingCount); // 10% random

        $products = collect();
        $excludedIds = [];

        // 1. Get special/featured products
        $specialProducts = $this->getSpecialProducts($specialCount, $excludedIds);
        $products = $products->merge($specialProducts);
        $excludedIds = array_merge($excludedIds, $specialProducts->pluck('id')->toArray());

        // 2. Get interest-based products (user's preferred categories)
        $interestProducts = $this->recommendationService
            ->getInterestBasedProducts($user->id, $interestCount, $excludedIds);
        $products = $products->merge($interestProducts);
        $excludedIds = array_merge($excludedIds, $interestProducts->pluck('id')->toArray());

        // 3. Get trending products (popular in user's categories)
        $trendingProducts = $this->recommendationService
            ->getTrendingProducts($user->id, $trendingCount, $excludedIds);
        $products = $products->merge($trendingProducts);
        $excludedIds = array_merge($excludedIds, $trendingProducts->pluck('id')->toArray());

        // 4. Fill remaining with diverse products
        $remainingProducts = $this->getRandomProducts($remainingCount, $excludedIds);
        $products = $products->merge($remainingProducts);

        return $products->shuffle(); // Shuffle to avoid predictable patterns
    }

    /**
     * Get recommendations for guest users
     */
    private function getGuestUserRecommendations(int $perPage): Collection
    {
        $specialCount = (int) floor($perPage * 0.4);  // 40% special products
        $latestCount = (int) floor($perPage * 0.3);   // 30% latest products
        $remainingCount = $perPage - ($specialCount + $latestCount); // 30% random

        $products = collect();
        $excludedIds = [];

        // 1. Get special products
        $specialProducts = $this->getSpecialProducts($specialCount, $excludedIds);
        $products = $products->merge($specialProducts);
        $excludedIds = array_merge($excludedIds, $specialProducts->pluck('id')->toArray());

        // 2. Get latest products
        $latestProducts = $this->getLatestProducts($latestCount, $excludedIds);
        $products = $products->merge($latestProducts);
        $excludedIds = array_merge($excludedIds, $latestProducts->pluck('id')->toArray());

        // 3. Fill remaining with random products
        $remainingProducts = $this->getRandomProducts($remainingCount, $excludedIds);
        $products = $products->merge($remainingProducts);

        return $products->shuffle();
    }

    /**
     * Get special/featured products
     */
    private function getSpecialProducts(int $limit, array $excludedIds = []): Collection
    {
        return Product::where('active', ProductActiveEnum::ACTIVE->value)
            ->where('level', LevelProductEnum::SPECIAL->value)
            ->whereNotIn('id', $excludedIds)
            ->where(function ($query) {
                $query->whereDoesntHave('category', function ($q) {
                    $q->where('type', CategoryTypeEnum::RESTAURANT->value);
                });
            })
            ->where(function ($query) {
                $query->where('type', CategoryTypeEnum::PRODUCT->value)
                    ->orWhere('type', CategoryTypeEnum::TENDER->value)
                    ->orWhere('type', CategoryTypeEnum::JOB->value)
                    ->orWhere('type', CategoryTypeEnum::SEARCH_JOB->value)
                    ->orWhere('type', CategoryTypeEnum::NEWS->value);
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>', now());
            })
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Get latest products (non-special)
     */
    private function getLatestProducts(int $limit, array $excludedIds = []): Collection
    {
        return Product::where('active', ProductActiveEnum::ACTIVE->value)
            ->whereNot('level', LevelProductEnum::SPECIAL->value)
            ->whereNotIn('id', $excludedIds)
            ->where(function ($query) {
                $query->whereDoesntHave('category', function ($q) {
                    $q->where('type', CategoryTypeEnum::RESTAURANT->value);
                });
            })
            ->where(function ($query) {
                $query->where('type', CategoryTypeEnum::PRODUCT->value)
                    ->orWhere('type', CategoryTypeEnum::TENDER->value)
                    ->orWhere('type', CategoryTypeEnum::JOB->value)
                    ->orWhere('type', CategoryTypeEnum::SEARCH_JOB->value)
                    ->orWhere('type', CategoryTypeEnum::NEWS->value);
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>', now());
            })
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get random products for diversity
     */
    private function getRandomProducts(int $limit, array $excludedIds = []): Collection
    {
        return Product::where('active', ProductActiveEnum::ACTIVE->value)
            ->whereNotIn('id', $excludedIds)
            ->where(function ($query) {
                $query->whereDoesntHave('category', function ($q) {
                    $q->where('type', CategoryTypeEnum::RESTAURANT->value);
                });
            })
            ->where(function ($query) {
                $query->where('type', CategoryTypeEnum::PRODUCT->value)
                    ->orWhere('type', CategoryTypeEnum::TENDER->value)
                    ->orWhere('type', CategoryTypeEnum::JOB->value)
                    ->orWhere('type', CategoryTypeEnum::SEARCH_JOB->value)
                    ->orWhere('type', CategoryTypeEnum::NEWS->value);
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>', now());
            })
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Track product views for analytics
     */
    private function trackProductViews(Collection $products): void
    {
        $productIds = $products->pluck('id')->toArray();
        $this->viewTrackingService->trackBulkViews($productIds);
    }


}
