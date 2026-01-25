<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\UserFollow;

final class GetMyFollowing
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        return  UserFollow::where('seller_id',auth()->id())->latest();
    }
}
