<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

final readonly class Suggestions
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $search=  $args['search']??null;
        return \App\Models\Product::active()->whereNotNull('name')->where(function ($query)use($search){
            $query->where('name', 'like', "%$search%");
            $query->orWher('expert', 'like', "%$search%");
        })->pluck('name')->toArray();
    }
}
