<?php declare(strict_types=1);

namespace App\GraphQL\Queries\HomeQuery;

use App\Enums\CategoryTypeEnum;
use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Interaction;
use App\Models\Product;
use App\Models\ProductView;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class HobbiesProduct
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $setting = Setting::first();
        // return Product::where('id', 0);
      /*  $products = Product::active()->where('power', '>', 20)->where(fn($query) => $query->whereDoesntHave('category', fn($query) => $query->where('type', CategoryTypeEnum::RESTAURANT->value)))
            ->where(function ($query) {
                $query->where('end_date', '>', now());
            })
            ->whereIn('type', [
                CategoryTypeEnum::PRODUCT->value,
                CategoryTypeEnum::TENDER->value,
                CategoryTypeEnum::JOB->value,
                CategoryTypeEnum::SEARCH_JOB->value,
                CategoryTypeEnum::NEWS->value,
            ])

            ->when(auth()->check(), fn($query) => $query->where(fn($q) => $q->whereIn('category_id', $this->getPopularCategoryProducts())
                ->orWhereIn('user_id', $this->getPopularSelelrProducts())
            ))
            ->where('created_at', '>=', now()->subDays($setting->options['recommended_month'] ?? 30))->inRandomOrder();*/
        $now=now();
        $products = Product::active()->where('id',0)
            ->whereHas('user',fn($q)=>$q->where('users.is_active', 1))

            ->where('power', '>', 20)
            ->whereDoesntHave('category', fn($query) =>
            $query->where('type', CategoryTypeEnum::RESTAURANT->value)
            )
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
            ->when(auth()->check(), fn($query) =>
            $query->where(function ($q) {
                $q->whereIn('category_id', $this->getPopularCategoryProducts())
                    ->orWhereIn('user_id', $this->getPopularSelelrProducts());
            })
            )
            ->where('created_at', '>=', now()->subDays($setting->options['recommended_month'] ?? 30))
            ->inRandomOrder();
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


}
