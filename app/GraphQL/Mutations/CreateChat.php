<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\CommunityTypeEnum;
use App\Models\Community;
use App\Models\User;

final class CreateChat
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $userId=auth()->id();
        $memberId=$args['memberId'];
        $member=User::find($memberId);
        $community = Community::whereHas('users', function($query) use ($userId) {
            $query->where('users.id', $userId);
        })->whereHas('users', function($query) use ($memberId) {
            $query->where('users.id', $memberId);
        })->where('type', CommunityTypeEnum::CHAT->value)
            ->withCount('users')
            ->having('users_count', '=', 2)
            ->first();

        if($community===null){
           $community=Community::create([
               'last_update'=>now(),
               'manager_id'=>$userId,
               'name'=>auth()->user()->name.' - '. $member?->seller_name,
               'type'=>CommunityTypeEnum::CHAT->value,
           ]);

           $community->users()->sync([$userId,$memberId]);
       }
       return [
           'community'=>$community,
           'user'=>auth()->user()
       ];
    }
}
