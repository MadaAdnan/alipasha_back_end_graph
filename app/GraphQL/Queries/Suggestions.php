<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\User;

final  class Suggestions
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $search=  $args['search']??null;
        $type=  $args['type']??'product';
        if($type=='seller'){
            return User::seller()->take(10)->whereNotNull('seller_name')->pluck('seller_name')->toArray();
        }else{
            return \App\Models\Product::active()
                ->whereNotNull('name')->where(function ($query)use($search){
                $query->where('name', 'like', "%$search%");
                $query->orWhere('expert', 'like', "%$search%");
            })
                ->where('type',$type)
                ->take(10)->pluck('name')->toArray();
        }


    }
}
