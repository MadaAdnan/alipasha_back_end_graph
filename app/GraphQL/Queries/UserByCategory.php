<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\User;

final  class UserByCategory
{
    /** @param  array{}  $args */
    public function __invoke( $_, array $args)
    {
       //$users=User::whereHas('products',fn($query)=>$query->where('category_id',$args['category_id']))->inRandomOrder()->get();


        $users = User::whereHas('products', function ($query) use ($args) {
            $query->where('category_id', $args['category_id']);
        })
            ->whereHas('products', function ($query) {
                $query->selectRaw('count(*) as product_count')
                    ->groupBy('user_id')
                    ->havingRaw('product_count >= ?', [5]); // التحقق من أن المستخدم يملك 5 منتجات أو أكثر
            })
            ->when(auth()->check() && auth()->user()->city_id!=null,fn($query)=>$query->orderByRaw("city_id = ? DESC, city_id ASC", [auth()->user()->city_id])) // ترتيب حسب مدينة المستخدم الحالي أولاً
            ->inRandomOrder();
       return $users;
    }
}
