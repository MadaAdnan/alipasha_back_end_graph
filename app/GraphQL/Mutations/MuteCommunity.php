<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Community;
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

        $community=Community::find($communityId);
        if(!$community){
            throw new GraphQLExceptionHandler('المجموعة غير موجودة');
        }
        if($community ) {
            $user=$community->users()->where('users.id',auth()->id())->first();
            if(!$user){
                throw new GraphQLExceptionHandler('المجموعة غير موجودة');
            }
            if ($user->pivot->notify) {
                $community->users()->syncWithPivotValues([$user->id], ['notify' => false], false);
            } else {
                $community->users()->syncWithPivotValues([$user->id], ['notify' => true], false);
            }
        }
        return $community;
    }
}
