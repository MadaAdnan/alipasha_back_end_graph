<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\User;

final class LeaveCommunity
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $communityId=$args['communityId'];
        /**
         * @var $user User
         */
        $user=auth()->user();
        $user->communities()->detach($communityId);
        return true;
    }
}
