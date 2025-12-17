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
        $setting = Setting::first();
        $now = now(); // خزّن الآن مرة واحدة لتجنب فروق زمنية صغيرة
$city=$args['city_id']??null;
$category=$args['category_id']??null;
        $popularCategories = [];//$this->getPopularCategoryProducts() ?? [];
        $popularSellers =[];// $this->getPopularSelelrProducts() ?? [];

        $productsQuery = Product::active()
            ->whereHas('user', function($q) use($city){
                $q->where('users.is_active', 1);
                if($city!=null){
                    $q->where('users.city_id', $city);
                }
            } )
            ->where('power', '>', 20)
            ->whereDoesntHave('category', fn($q) => $q->where('type', CategoryTypeEnum::RESTAURANT->value))
            ->whereNot('level', LevelProductEnum::SPECIAL->value)
            ->where(function($query)use($category){

                if($category!=null){
                    $query->where('category_id',$category);
                }

            })

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
            ])
            ->where('created_at', '>=', now()->subDays($setting->options['recommended_month'] ?? 30));
        if (auth()->check()) {
            $productsQuery->where(function ($q) use ($popularCategories, $popularSellers) {
                if (!empty($popularCategories)) {
                    $q->whereNotIn('category_id', $popularCategories);
                }
                if (!empty($popularSellers)) {
                    $q->whereNotIn('user_id', $popularSellers);
                }
            });
        }

        $products = $productsQuery->inRandomOrder();
        $ids = $products->pluck('id')->toArray();
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
