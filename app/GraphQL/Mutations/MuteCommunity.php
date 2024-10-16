<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;

final class MuteCommunity
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
        $community=$user->communities()->find($communityId);
        if($community && $community->pivot->notify) {
            if ($community->pivot->notify) {
                $community->users()->syncWithPivotValues([$user->id], ['notify' => false], false);
            } else {
                $community->users()->syncWithPivotValues([$user->id], ['notify' => true], false);
            }
        }
        return $community;
    }
}
