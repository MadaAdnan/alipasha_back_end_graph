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
        $user = auth()->user();
        $categorisId = \DB::table('products')->where('user_id', auth()->id())->pluck('sub1_id')->toArray();
        return Category::whereIn('id', $categorisId)->get();
    }
}
