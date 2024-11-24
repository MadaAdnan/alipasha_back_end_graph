<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\User;

final  class SearchSeller
{
    /** @param  array{}  $args */
    public function __invoke( $_, array $args)
    {
        $sellers=User::whereHas('products')->where('seller_name','like',"%{$args['search']}%")->orWhere('id',$args['search'])->take(35)->inRandomOrder()->get();
        return $sellers;
    }
}
