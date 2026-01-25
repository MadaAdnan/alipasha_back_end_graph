<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\UserFollow;

final class GetMyFollowers
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
      return  UserFollow::where('user_id',auth()->id())->latest();
    }
}
