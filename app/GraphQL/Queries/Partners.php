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
        $category_id = $args['category_id'] ?? null;
        return Partner::where('type', PartnerTypeEnum::PARTNER->value)
            ->when($category_id, fn($query) => $query->where('category_id', $category_id))
            ->latest();
    }
}
