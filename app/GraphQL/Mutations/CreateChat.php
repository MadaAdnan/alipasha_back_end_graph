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
       $community=Community::whereHas('users',fn($query)=>$query->where('users.id',auth()->id())->where('users.id',$memberId))->where('type',CommunityTypeEnum::CHAT->value)->first();
       if($community==null){
           $community=Community::create([
               'last_update'=>now(),
               'manager_id'=>$userId,
               'name'=>auth()->user()->name.' - '. User::find($memberId)?->seller_name,
               'type'=>CommunityTypeEnum::CHAT->value,
           ]);

           $community->users()->sync([$userId,$memberId]);
       }
       return $community;
    }
}
