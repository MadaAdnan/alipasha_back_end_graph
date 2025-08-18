<?php declare(strict_types=1);

namespace App\GraphQL\Queries\HomeQuery;

use App\Enums\CategoryTypeEnum;
use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Interaction;
use App\Models\Product;
use App\Models\Setting;

final class RecommendedSpecialProducts
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $setting = Setting::first();
        
        // Get products that are special and active
        $products = Product::where([
                'active' => ProductActiveEnum::ACTIVE->value,
                'level' => LevelProductEnum::SPECIAL->value
            ])
            ->where(fn($query) => $query->whereDoesntHave('category', fn($query) => $query->where('type', CategoryTypeEnum::RESTAURANT->value)))
            ->whereIn('type', [
                CategoryTypeEnum::PRODUCT->value,
                CategoryTypeEnum::TENDER->value,
                CategoryTypeEnum::JOB->value,
                CategoryTypeEnum::SEARCH_JOB->value,
                CategoryTypeEnum::NEWS->value,
            ])
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>', now()->timezone('UTC'));
            })
            ->where('created_at', '>=', now()->subDays($setting->options['recommended_month'] ?? 30))
            ->inRandomOrder()
            ->when(auth()->check(), function ($query) {
                // For authenticated users, prioritize products from categories they interacted with
                $popularCategoryIds = $this->getPopularCategoryProducts();
                if (!empty($popularCategoryIds)) {
                    // First, try to get special products from user's popular categories
                    $query->orderByRaw("FIELD(category_id, " . implode(',', $popularCategoryIds) . ") DESC")
                        ->orderByRaw("category_id NOT IN (" . implode(',', $popularCategoryIds) . ")");
                }
                return $query;
            });

        // Update product views
        $ids = $products->pluck('id')->toArray();
        $today = today();

        \DB::transaction(function () use ($ids, $today) {
            // تحديث السجلات الموجودة
            \DB::table('product_views')
                ->whereIn('product_id', $ids)
                ->whereDate('view_at', $today)
                ->update(['count' => \DB::raw('count + 1')]);
            $existingIds = \DB::table('product_views')
                ->whereIn('product_id', $ids)
                ->whereDate('view_at', $today)
                ->pluck('product_id')
                ->toArray();
            $newIds = array_diff($ids, $existingIds);
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

                \DB::table('product_views')->insert($inserts);
            }
        });
        
        return $products;
    }

    private function getPopularCategoryProducts()
    {
        return Interaction::where('user_id', auth()->id())
            ->whereNotNull('category_id')
            ->latest()
            ->groupBy('category_id')
            ->orderByRaw('SUM(visited) DESC')
            ->pluck('category_id')
            ->toArray();
    }
}