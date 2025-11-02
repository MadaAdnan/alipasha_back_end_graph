<?php declare(strict_types=1);

namespace App\GraphQL\Queries\SliderGroup;

use App\Enums\CategoryTypeEnum;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;

final  class ActivitySeller
{
    /** @param array{} $args */
    public function __invoke($_, array $args)
    {

        $excludedTypes = [
            CategoryTypeEnum::JOB->value,
            CategoryTypeEnum::SEARCH_JOB->value,
            CategoryTypeEnum::TENDER->value,
            CategoryTypeEnum::RESTAURANT->value,
        ];

        return User::seller()
            // فقط البائعين الذين لا يملكون منتجات من الأنواع المستبعدة
            ->whereDoesntHave('products', function ($query) use ($excludedTypes) {
                $query->whereIn('type', $excludedTypes);
            })
            // الذين لديهم منتجات أُنشئت خلال آخر شهر
            ->whereHas('products', function ($query) {
                $query->where('created_at', '>=', now()->subMonth());
            })
            // لديهم صور (وسائط من نوع image)
            ->whereHas('media', function ($query) {
                $query->where('collection_name', 'image');
            })
            // نحسب عدد المنتجات
            ->withCount('products')
            // أكثر من 50 منتج
            ->having('products_count', '>', 5)
            // ترتيب عشوائي
            ->inRandomOrder()
            // عدد محدد
            ->take(5)
            ->get();

    }
}
