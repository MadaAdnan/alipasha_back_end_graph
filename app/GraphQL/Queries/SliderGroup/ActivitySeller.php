<?php declare(strict_types=1);

namespace App\GraphQL\Queries\SliderGroup;

use App\Models\Product;
use App\Models\Setting;
use App\Models\User;

final  class ActivitySeller
{
    /** @param array{} $args */
    public function __invoke($_, array $args)
    {

            return User::seller()
                ->whereHas('products', fn($q) =>
                $q->where('created_at', '>=', now()->subMonth()) // منتج خلال آخر شهر
                )
                ->having('products_count', '>', 50)
                ->whereHas('media', fn($query) => $query->where('collection_name', 'image'))->inRandomOrder()->take(5)->get();

    }
}
