<?php declare(strict_types=1);

namespace App\GraphQL\Queries\HomeQuery;

use App\Enums\CategoryTypeEnum;
use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Interaction;
use App\Models\Product;
use App\Models\Setting;

final class SpecialProduct
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $categoryId= $args['category_id'] ?? null;
        $cityId= $args['city_id'] ?? null;
        $setting = Setting::first();
        $now = now();
        $products = Product::where(['active' => ProductActiveEnum::ACTIVE->value,
            'level' => LevelProductEnum::SPECIAL->value])
            ->whereHas('user', fn($q) => $q->where('users.is_active', 1))
            ->where(fn($query) => $query->whereDoesntHave('category', fn($query) => $query->where('type', CategoryTypeEnum::RESTAURANT->value)))
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')                    // أظهر المنتجات التي end_date = NULL
                ->orWhere('end_date', '>', $now)           // أو التي تاريخها في المستقبل
                ->orWhere('end_date', '')                  // أو حقل فارغ '' (إذا كان لديك مثل هذه القيم)
                ->orWhereRaw("end_date = '0000-00-00' OR end_date = '0000-00-00 00:00:00'"); // تعامل مع الـ zero-date إن وجد
            })
            ->whereIn('type', [
                CategoryTypeEnum::PRODUCT->value,
                CategoryTypeEnum::TENDER->value,
                CategoryTypeEnum::JOB->value,
                CategoryTypeEnum::SEARCH_JOB->value,
                CategoryTypeEnum::NEWS->value,
            ])->where('created_at', '>=', now()->subDays($setting->options['recommended_month'] ?? 30))
             ->orderByRaw(
                "
        CASE
            WHEN ? IS NOT NULL AND category_id = ? THEN 0
            ELSE 1
        END,
        CASE
            WHEN ? IS NOT NULL AND city_id = ? THEN 0
            ELSE 1
        END,
        RAND()
        ",
                [$categoryId, $categoryId, $cityId, $cityId]
            )
        ;

        $ids = $products->pluck('id')->toArray();
        $ratio = $setting->ratio_view_home;
        $half = ceil(count($ids) * $ratio ?? 0.5);
        $idsChunks = array_chunk($ids, (int)$half); // يقسم المصفوفة إلى أجزاء

        list($firstHalf, $secondHalf) = $idsChunks; // نفصلها في متغيرين
        $today = today();

        \DB::transaction(function () use ($firstHalf, $today) {

            // تحديث السجلات الموجودة
            \DB::table('product_views')
                ->whereIn('product_id', $firstHalf)
                ->whereDate('view_at', $today)
                ->update(['count' => \DB::raw('count + 1')]);
            $existingIds = \DB::table('product_views')
                ->whereIn('product_id', $firstHalf)
                ->whereDate('view_at', $today)
                ->pluck('product_id')
                ->toArray();
            $newIds = array_diff($firstHalf, $existingIds);
            if (
                !empty($newIds)) {
                $inserts = array_map(function ($firstHalf) use ($today) {
                    return [
                        'product_id' => $firstHalf,
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

        return Interaction::where('user_id', auth()->id())->whereNotNull('category_id')
            ->latest()
            ->groupBy('category_id')
            ->orderByRaw('SUM(visited) DESC')
            ->pluck('category_id')->toArray();
    }

    private function getPopularSelelrProducts()
    {
        return Interaction::where('user_id', auth()->id())->whereNotNull('seller_id')
            ->groupBy('seller_id')
            ->pluck('seller_id')->toArray();
    }

    private function newQuery()
    {
        $sellers = [];
        $setting = Setting::first();
        if (auth()->check()) {
            $sellers = auth()->user()->followers->pluck('seller_id')->toArray();
        }
        $specialLevel = LevelProductEnum::SPECIAL->value;
        $now = now();
        $products = Product::active()->whereIn('type', [
            CategoryTypeEnum::PRODUCT->value,
            CategoryTypeEnum::TENDER->value,
            CategoryTypeEnum::JOB->value,
            CategoryTypeEnum::SEARCH_JOB->value,
            CategoryTypeEnum::NEWS->value,
        ])
            ->whereHas('user', fn($q) => $q->where('users.is_active', 1))
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')                    // أظهر المنتجات التي end_date = NULL
                ->orWhere('end_date', '>', $now)           // أو التي تاريخها في المستقبل
                ->orWhere('end_date', '')                  // أو حقل فارغ '' (إذا كان لديك مثل هذه القيم)
                ->orWhereRaw("end_date = '0000-00-00' OR end_date = '0000-00-00 00:00:00'"); // تعامل مع الـ zero-date إن وجد
            })
            //  ->where('created_at', '>=', now()->subDays($setting->options['recommended_month'] ?? 30))->inRandomOrder()
            ->where('power', '>=', 20)
            ->orderByRaw("
        (level = ?) DESC,
        (user_id IN (" . ($sellers ? implode(',', $sellers) : 0) . ")) DESC,
        RAND()
    ", [$specialLevel]);
        return $products;
    }
}
