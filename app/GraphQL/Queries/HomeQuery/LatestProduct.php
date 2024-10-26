<?php declare(strict_types=1);

namespace App\GraphQL\Queries\HomeQuery;

use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Product;

final class LatestProduct
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        return Product::where('active',ProductActiveEnum::ACTIVE->value)
            ->whereNot('level',LevelProductEnum::SPECIAL->value)
            ->latest()->inRandomOrder();
    }
}
