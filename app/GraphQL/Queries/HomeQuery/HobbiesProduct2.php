<?php declare(strict_types=1);

namespace App\GraphQL\Queries\HomeQuery;

use App\Enums\CategoryTypeEnum;
use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Interaction;
use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class HobbiesProduct2
{
    /**
     * @param null $_
     * @param array{page?: int, perPage?: int} $args
     */
    public function __invoke($_, array $args)
    {
        $perPage = $args['first'] ?? 50;
        $user = auth()->user();

        // Get smart recommendations based on user interests
        $products = $this->getInterestBasedRecommendations($user, $perPage);

        // Track views for analytics
        $this->trackProductViews($products);

        return $products;
    }

    /**
     * Get recommendations based on user interests and behavior
     */
    private function getInterestBasedRecommendations($user, int $perPage): Collection
    {
        if (!$user) {
            // For guests, return trending and special products
            return $this->getGuestRecommendations($perPage);
        }

        // For authenticated users, get personalized recommendations
        return $this->getPersonalizedRecommendations($user, $perPage);
    }

    /**
     * Get personalized recommendations for authenticated users
     */
    private function getPersonalizedRecommendations($user, int $perPage): Collection
    {
        // Calculate distribution
        $interestCount = (int) floor($perPage * 0.6);     // 60% interest-based
        $trendingCount = (int) floor($perPage * 0.25);    // 25% trending
        $specialCount = (int) floor($perPage * 0.15);     // 15% special

        $products = collect();
        $excludedIds = [];

        // 1. Get products from user's interested categories
        $interestProducts = $this->getUserInterestProducts($user->id, $interestCount, $excludedIds);
        $products = $products->merge($interestProducts);
        $excludedIds = array_merge($excludedIds, $interestProducts->pluck('id')->toArray());

        // 2. Get trending products in user's categories
        $trendingProducts = $this->getTrendingInUserCategories($user->id, $trendingCount, $excludedIds);
        $products = $products->merge($trendingProducts);
        $excludedIds = array_merge($excludedIds, $trendingProducts->pluck('id')->toArray());

        // 3. Add some special products for variety
        $specialProducts = $this->getSpecialProducts($specialCount, $excludedIds);
        $products = $products->merge($specialProducts);

        return $products->shuffle(); // Shuffle for variety
    }

    /**
     * Get recommendations for guest users
     */
    private function getGuestRecommendations(int $perPage): Collection
    {
        $specialCount = (int) floor($perPage * 0.4);  // 40% special
        $latestCount = (int) floor($perPage * 0.6);   // 60% latest

        $products = collect();
        $excludedIds = [];

        // Get special products
        $specialProducts = $this->getSpecialProducts($specialCount, $excludedIds);
        $products = $products->merge($specialProducts);
        $excludedIds = array_merge($excludedIds, $specialProducts->pluck('id')->toArray());

        // Get latest products
        $latestProducts = $this->getLatestProducts($latestCount, $excludedIds);
        $products = $products->merge($latestProducts);

        return $products->shuffle();
    }

    /**
     * Get products from user's interested categories
     */
    private function getUserInterestProducts(int $userId, int $limit, array $excludedIds = []): Collection
    {
        // Get user's preferred categories with caching
        $cacheKey = "user_interests_{$userId}";
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
     * Get trending products in user's preferred categories
     */
    private function getTrendingInUserCategories(int $userId, int $limit, array $excludedIds = []): Collection
    {
        // Get user's categories
        $userCategories = Interaction::where('user_id', $userId)
            ->whereNotNull('category_id')
            ->pluck('category_id')
            ->toArray();

        if (empty($userCategories)) {
            return $this->getLatestProducts($limit, $excludedIds);
        }

        // Get trending products from the last 7 days in user's categories
        $trendingProductIds = ProductView::where('view_at', '>=', now()->subDays(7))
            ->join('products', 'product_views.product_id', '=', 'products.id')
            ->whereIn('products.category_id', $userCategories)
            ->select('product_views.product_id')
            ->groupBy('product_views.product_id')
            ->orderByRaw('SUM(product_views.count) DESC')
            ->limit($limit * 2) // Get more to filter later
            ->pluck('product_views.product_id')
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

        if (!empty($trendingProductIds)) {
            $query->whereIn('id', $trendingProductIds);
        } else {
            // Fallback to latest products in user's categories
            $query->whereIn('category_id', $userCategories)
                ->latest('created_at');
        }

        return $query->inRandomOrder()
            ->limit($limit)
            ->get();
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
     * Get latest products
     */
    private function getLatestProducts(int $limit, array $excludedIds = []): Collection
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
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Track product views for analytics
     */
    private function trackProductViews(Collection $products): void
    {
        if ($products->isEmpty()) {
            return;
        }

        $productIds = $products->pluck('id')->toArray();
        $today = today();

        DB::transaction(function () use ($productIds, $today) {
            // Update existing records
            DB::table('product_views')
                ->whereIn('product_id', $productIds)
                ->whereDate('view_at', $today)
                ->update(['count' => DB::raw('count + 1')]);

            // Get IDs that already have records today
            $existingIds = DB::table('product_views')
                ->whereIn('product_id', $productIds)
                ->whereDate('view_at', $today)
                ->pluck('product_id')
                ->toArray();

            // Find IDs that need new records
            $newIds = array_diff($productIds, $existingIds);

            if (!empty($newIds)) {
                $inserts = array_map(function ($id) use ($today) {
                    return [
                        'product_id' => $id,
                        'view_at' => $today,
                        'count' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }, $newIds);

                DB::table('product_views')->insert($inserts);
            }
        });
    }
}
