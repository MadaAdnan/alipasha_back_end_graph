<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\PartnerTypeEnum;
use App\Models\Partner;

final class Sellers
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $city_id = $args['city_id'] ?? null;
        $category_id = $args['category_id'] ?? null;
        return Partner::where('type', PartnerTypeEnum::SELLER->value)
            ->when($city_id, fn($query) => $query->where('city_id', $city_id))
            ->when($category_id, fn($query) => $query->where('category_id', $category_id))
            ->orderBy('city_id');
    }
}
