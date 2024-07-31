<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Category;

final class MainCategories
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
       return  Category::where(['is_active'=>true,'is_main'=>true/*,'type' => 'product'*/])
           ->orderByRaw("FIELD(type, 'product', 'job', 'search_job','tender','service','news')")
           ->get();
    }
}
