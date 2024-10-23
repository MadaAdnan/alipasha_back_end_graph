<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Category;

final class CategoryBySeller
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $userId = $args['sellerId'];
        $categorisId = \DB::table('products')->where('user_id', $userId)->pluck('sub1_id')->toArray();
        return Category::whereIn('id', $categorisId) ->orderBy('sortable')->orderBy('id')->get();
    }
}
