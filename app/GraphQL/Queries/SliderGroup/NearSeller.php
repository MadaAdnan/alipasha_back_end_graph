<?php declare(strict_types=1);

namespace App\GraphQL\Queries\SliderGroup;

use App\Enums\CategoryTypeEnum;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;

final  class NearSeller
{
    /** @param array{} $args */
    public function __invoke($_, array $args)
    {
        if (auth()->check()) {
            $excludedTypes = [
                CategoryTypeEnum::JOB->value,
                CategoryTypeEnum::SEARCH_JOB->value,
                CategoryTypeEnum::TENDER->value,
                CategoryTypeEnum::RESTAURANT->value,
            ];
            return User::seller()->whereIsActive(true)
                ->whereHas('media', fn($query) => $query->where('collection_name', 'image'))
                // فقط البائعين الذين لا يملكون منتجات من الأنواع المستبعدة
                ->whereDoesntHave('products', function ($query) use ($excludedTypes) {
                    $query->whereIn('type', $excludedTypes);
                })
                ->having('products_count', '>', 5)
                ->where('city_id', auth()->user()->city_id)
                ->inRandomOrder()->take(5)->get();
        }
        return [];
    }
}
