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
       $community=Community::where('type',CommunityTypeEnum::CHAT->value)->whereHas('users',fn($query)=>
       $query->where(
           fn($q)=>$q->where('users.id',auth()->id())->where('users.id',$memberId)))
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
       return $community;
    }
}
