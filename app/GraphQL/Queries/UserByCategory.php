<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\User;

final  class UserByCategory
{
    /** @param  array{}  $args */
    public function __invoke( $_, array $args)
    {
       $users=User::where('category_id',$args['category_id'])->inRandomOrder();
       return $users;
    }
}
