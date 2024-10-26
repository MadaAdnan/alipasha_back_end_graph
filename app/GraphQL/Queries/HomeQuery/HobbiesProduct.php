<?php declare(strict_types=1);

namespace App\GraphQL\Queries\HomeQuery;

use App\Enums\ProductActiveEnum;
use App\Models\Interaction;
use App\Models\Product;

final class HobbiesProduct
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        return Product::where('active',ProductActiveEnum::ACTIVE->value)->whereIn('category_id',$this->getPopularCategoryProducts())->inRandomOrder();
    }

    private function getPopularCategoryProducts()
    {
        return Interaction::where('user_id',auth()->id())->whereNotNull('category_id')
            ->groupBy('category_id')
            ->orderByRaw('SUM(visited) DESC')
            ->pluck('category_id')->toArray();
    }
}
