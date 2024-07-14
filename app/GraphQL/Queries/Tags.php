<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Product;

final class Tags
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        return array_filter(Product::where('type', $args['type'])->pluck('tags')->flatten()->unique()->toArray());
    }
}
