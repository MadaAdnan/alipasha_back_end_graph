<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\City;

final class CitiesByCategory
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
       $categoryId=$args['categoryId'];
       return City::whereHas('products',fn($query)=>$query->where(['products.sub1_id',$categoryId,'products.active','active']))->orderBy('sortable');
    }
}
