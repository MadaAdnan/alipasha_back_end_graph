<?php declare(strict_types=1);

namespace App\GraphQL\Queries\SliderGroup;

use App\Models\Product;
use App\Models\Setting;
use App\Models\User;

final  class NearSeller
{
    /** @param array{} $args */
    public function __invoke($_, array $args)
    {
        if (auth()->check()) {
            return User::seller()
                ->whereHas('media', fn($query) => $query->where('collection_name', 'image'))
                ->having('products_count', '>', 50)
                ->where('city_id', auth()->user()->city_id)
                ->inRandomOrder()->take(5)->get();
        }
        return [];
    }
}
