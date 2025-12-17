<?php declare(strict_types=1);

namespace App\GraphQL\Queries\HomeQuery;

use App\Enums\CategoryTypeEnum;
use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Interaction;
use App\Models\Product;
use App\Models\Setting;

final class LatestProduct
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $popularCategories = [];
        $popularSellers =[];
        $categoryId= $args['category_id'] ?? null;
        $cityId= $args['city_id'] ?? null;
        $setting = Setting::first();
        $now = now(); // خزّن الآن مرة واحدة لتجنب فروق زمنية صغيرة



        $products = Product::active()

            ->whereHas('user', fn($q) => $q->where('users.is_active', 1))
            ->where('power', '>', 20)
            ->whereDoesntHave('category', fn($q) => $q->where('type', CategoryTypeEnum::RESTAURANT->value))
            ->whereNot('level', LevelProductEnum::SPECIAL->value)
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')
                ->orWhere('end_date', '>', $now)
                ->orWhere('end_date', '')
                ->orWhereRaw("end_date = '0000-00-00' OR end_date = '0000-00-00 00:00:00'");
            })
            ->whereIn('type', [
                CategoryTypeEnum::PRODUCT->value,
                CategoryTypeEnum::TENDER->value,
                CategoryTypeEnum::JOB->value,
                CategoryTypeEnum::SEARCH_JOB->value,
                CategoryTypeEnum::NEWS->value,
            ])
            ->where('created_at', '>=', now()->subDays($setting->options['recommended_month'] ?? 30))
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

        $ids = $products->pluck('id')?->toArray()??[];

        $ratio = $setting->ratio_view_home;
        $half = ceil(count($ids) * $ratio ?? 0.5); // نحسب النصف (في حال كان العدد فردي)

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
}
