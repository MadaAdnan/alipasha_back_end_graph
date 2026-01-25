<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\IsActiveEnum;
use App\Enums\ProductActiveEnum;

final class SearchProduct
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        if (isset($args['search']) && !empty($args['search'])) {
            $products = \App\Models\Product::search($args['search']);
        } else {
            $products = \App\Models\Product::query();
        }

        $products = $products->where('active', ProductActiveEnum::ACTIVE->value)
            ->where('type', $args['type']);
        return $products;
    }
}
