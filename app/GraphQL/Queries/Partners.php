<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\PartnerTypeEnum;
use App\Models\Partner;

final class Partners
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $city_id = $args['city_id'] ?? null;
        return Partner::where('type', PartnerTypeEnum::PARTNER->value)
            ->when($city_id, fn($query) => $query->where('city_id', $city_id))
            ->orderBy('city_id');
    }
}
