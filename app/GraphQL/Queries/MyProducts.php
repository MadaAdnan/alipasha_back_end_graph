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
           ->when(isset($args['search']) && !empty($args['search']),fn($query)=>$query ->where(fn($query) => $query->where('name', 'LIKE', "%{$args['search']}%")->orWhere('expert', 'LIKE', "%{$args['search']}%")))
//           ->when(isset($args['categoryId']),fn($query)=>$query ->where('sub1_id', $args['categoryId']))
            ->orderBy('created_at','desc');
    }
}
