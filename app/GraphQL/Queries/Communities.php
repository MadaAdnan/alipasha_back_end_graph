<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Community;

final class Communities
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $search=$args['search'];
       $communities=Community::where(fn($query)=>$query->where('user_id',auth()->id())->orWhere('seller_id',auth()->id()))

           ->when(!empty($search),function($query)use($search){
               $query->where(fn($query)=>$query->whereHas('user',fn($q)=>$q->whereNot('id',auth()->id())->where('name','like',"%$search%"))
                   ->orWhereHas('user',fn($q)=>$q->whereNot('id',auth()->id())->where('seller_name','like',"%$search%")));
               $query->orWhere(fn($query)=>$query->whereHas('seller',fn($q)=>$q->whereNot('id',auth()->id())->where('name','like',"%$search%"))
                   ->orWhereHas('seller',fn($q)=>$q->whereNot('id',auth()->id())->where('seller_name','like',"%$search%")));
           })

           ->latest('last_change');
       return $communities;
    }
}
