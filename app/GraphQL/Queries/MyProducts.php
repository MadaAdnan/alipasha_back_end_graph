<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

final class MyProducts
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        return \App\Models\Product::where('user_id', auth()->id())
            ->where(fn($query) => $query->where('name', 'LIKE', "%{$args['search']}%")->orWhere('expert', 'LIKE', "%{$args['search']}%"));
    }
}
