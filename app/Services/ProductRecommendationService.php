<?php declare(strict_types=1);

namespace App\Services;

use App\Enums\CategoryTypeEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Interaction;
use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductRecommendationService
{
    /**
     * Get products based on user's category interactions
     */
    public function getInterestBasedProducts(int $userId, int $limit, array $excludedIds = []): Collection
    {
        $cacheKey = "user_interests_{$userId}";
        
        // Get user's preferred categories (cached for 1 hour)
        $preferredCategories = Cache::remember($cacheKey, 3600, function () use ($userId) {
            return Interaction::where('user_id', $userId)
                ->whereNotNull('category_id')
                ->select('category_id')
                ->groupBy('category_id')
                ->orderByRaw('SUM(visited) DESC')
                ->limit(10) // Top 10 categories
                ->pluck('category_id')
                ->toArray();
        });

        if (empty($preferredCategories)) {
            return collect();
        }

        return Product::where('active', ProductActiveEnum::ACTIVE->value)
            ->whereIn('category_id', $preferredCategories)
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
     * Get trending products based on recent views and user's interests
     */
    public function getTrendingProducts(int $userId, int $limit, array $excludedIds = []): Collection
    {
        $cacheKey = "trending_products_{$userId}";
        
        return Cache::remember($cacheKey, 1800, function () use ($userId, $limit, $excludedIds) {
            // Get user's preferred categories
            $userCategories = Interaction::where('user_id', $userId)
                ->whereNotNull('category_id')
                ->pluck('category_id')
                ->toArray();

            // Get trending products from the last 7 days
            $trendingProductIds = ProductView::where('view_at', '>=', now()->subDays(7))
                ->select('product_id')
                ->groupBy('product_id')
                ->orderByRaw('SUM(count) DESC')
                ->limit($limit * 3) // Get more to filter later
                ->pluck('product_id')
                ->toArray();

            $query = Product::where('active', ProductActiveEnum::ACTIVE->value)
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
                });

            // Prioritize trending products in user's categories
            if (!empty($userCategories) && !empty($trendingProductIds)) {
                $query->where(function ($q) use ($userCategories, $trendingProductIds) {
                    $q->whereIn('category_id', $userCategories)
                        ->whereIn('id', $trendingProductIds);
                })->orWhere(function ($q) use ($trendingProductIds) {
                    $q->whereIn('id', $trendingProductIds);
                });
            } elseif (!empty($trendingProductIds)) {
                $query->whereIn('id', $trendingProductIds);
            }

            return $query->inRandomOrder()
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get products from sellers that user has interacted with
     */
    public function getSellerBasedProducts(int $userId, int $limit, array $excludedIds = []): Collection
    {
        $preferredSellers = Interaction::where('user_id', $userId)
            ->whereNotNull('seller_id')
            ->select('seller_id')
            ->groupBy('seller_id')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(5) // Top 5 sellers
            ->pluck('seller_id')
            ->toArray();

        if (empty($preferredSellers)) {
            return collect();
        }

        return Product::where('active', ProductActiveEnum::ACTIVE->value)
            ->whereIn('user_id', $preferredSellers)
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
     * Get similar products based on category hierarchy
     */
    public function getSimilarProducts(int $productId, int $limit = 6): Collection
    {
        $product = Product::find($productId);
        
        if (!$product) {
            return collect();
        }

        return Product::where('active', ProductActiveEnum::ACTIVE->value)
            ->where('id', '!=', $productId)
            ->where(function ($query) use ($product) {
                $query->where('category_id', $product->category_id)
                    ->orWhere('sub1_id', $product->sub1_id)
                    ->orWhere('sub2_id', $product->sub2_id);
            })
            ->where(function ($query) {
                $query->whereDoesntHave('category', function ($q) {
                    $q->where('type', CategoryTypeEnum::RESTAURANT->value);
                });
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
     * Clear user-specific caches
     */
    public function clearUserCache(int $userId): void
    {
        Cache::forget("user_interests_{$userId}");
        Cache::forget("trending_products_{$userId}");
    }

    /**
     * Get user interaction statistics
     */
    public function getUserInteractionStats(int $userId): array
    {
        $stats = Interaction::where('user_id', $userId)
            ->select([
                DB::raw('COUNT(DISTINCT category_id) as categories_count'),
                DB::raw('COUNT(DISTINCT seller_id) as sellers_count'),
                DB::raw('SUM(visited) as total_visits')
            ])
            ->first();

        return [
            'categories_interacted' => $stats->categories_count ?? 0,
            'sellers_interacted' => $stats->sellers_count ?? 0,
            'total_visits' => $stats->total_visits ?? 0,
        ];
    }
}
