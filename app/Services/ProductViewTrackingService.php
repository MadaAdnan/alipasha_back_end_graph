<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductViewTrackingService
{
    /**
     * Track views for multiple products in bulk
     */
    public function trackBulkViews(array $productIds): void
    {
        if (empty($productIds)) {
            return;
        }

        $today = Carbon::today();

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

    /**
     * Track view for a single product
     */
    public function trackSingleView(int $productId): void
    {
        $this->trackBulkViews([$productId]);
    }

    /**
     * Get view statistics for a product
     */
    public function getProductViewStats(int $productId): array
    {
        $stats = DB::table('product_views')
            ->where('product_id', $productId)
            ->select([
                DB::raw('SUM(count) as total_views'),
                DB::raw('COUNT(DISTINCT view_at) as days_viewed'),
                DB::raw('AVG(count) as avg_daily_views'),
                DB::raw('MAX(count) as max_daily_views')
            ])
            ->first();

        $recentViews = DB::table('product_views')
            ->where('product_id', $productId)
            ->where('view_at', '>=', now()->subDays(7))
            ->sum('count');

        return [
            'total_views' => $stats->total_views ?? 0,
            'days_viewed' => $stats->days_viewed ?? 0,
            'avg_daily_views' => round($stats->avg_daily_views ?? 0, 2),
            'max_daily_views' => $stats->max_daily_views ?? 0,
            'recent_views' => $recentViews,
        ];
    }

    /**
     * Get trending products based on views
     */
    public function getTrendingProductIds(int $days = 7, int $limit = 50): array
    {
        return DB::table('product_views')
            ->where('view_at', '>=', now()->subDays($days))
            ->select('product_id')
            ->groupBy('product_id')
            ->orderByRaw('SUM(count) DESC')
            ->limit($limit)
            ->pluck('product_id')
            ->toArray();
    }

    /**
     * Get view statistics for multiple products
     */
    public function getBulkViewStats(array $productIds): array
    {
        if (empty($productIds)) {
            return [];
        }

        $stats = DB::table('product_views')
            ->whereIn('product_id', $productIds)
            ->select([
                'product_id',
                DB::raw('SUM(count) as total_views'),
                DB::raw('COUNT(DISTINCT view_at) as days_viewed')
            ])
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id')
            ->toArray();

        // Ensure all product IDs are represented
        $result = [];
        foreach ($productIds as $productId) {
            $result[$productId] = [
                'total_views' => $stats[$productId]->total_views ?? 0,
                'days_viewed' => $stats[$productId]->days_viewed ?? 0,
            ];
        }

        return $result;
    }

    /**
     * Clean up old view records (older than specified days)
     */
    public function cleanupOldViews(int $daysToKeep = 90): int
    {
        $cutoffDate = now()->subDays($daysToKeep);
        
        return DB::table('product_views')
            ->where('view_at', '<', $cutoffDate)
            ->delete();
    }

    /**
     * Get daily view trends for a product
     */
    public function getDailyViewTrend(int $productId, int $days = 30): array
    {
        $startDate = now()->subDays($days);
        
        $views = DB::table('product_views')
            ->where('product_id', $productId)
            ->where('view_at', '>=', $startDate)
            ->select(['view_at', 'count'])
            ->orderBy('view_at')
            ->get()
            ->keyBy('view_at')
            ->toArray();

        // Fill in missing dates with 0 views
        $trend = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $trend[$date] = $views[$date]->count ?? 0;
        }

        return $trend;
    }

    /**
     * Get top viewed products for a specific time period
     */
    public function getTopViewedProducts(int $days = 7, int $limit = 10): array
    {
        return DB::table('product_views')
            ->join('products', 'product_views.product_id', '=', 'products.id')
            ->where('product_views.view_at', '>=', now()->subDays($days))
            ->where('products.active', 'active')
            ->select([
                'products.id',
                'products.name',
                DB::raw('SUM(product_views.count) as total_views')
            ])
            ->groupBy('products.id', 'products.name')
            ->orderByRaw('SUM(product_views.count) DESC')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
