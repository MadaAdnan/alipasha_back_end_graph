<?php

namespace App\Observers;

use App\Enums\CommunityTypeEnum;
use App\Enums\LevelUserEnum;
use App\Events\CreateCommunityEvent;
use App\Jobs\SendFirebaseNotificationJob;
use App\Models\Community;
use App\Models\User;
use App\Service\SendNotifyHelper;
use Mockery\Exception;

class CommunityObServe
{
    /**
     * Handle the Community "created" event.
     */
    public function creating(Community $community): void
    {
        $community->last_update=now();
    }

    public function created(Community $community): void
    {
        try{
          //  event(new CreateCommunityEvent($community));
        }catch (Exception | \Error $e){}
        if($community->is_global){
            User::where('level',LevelUserEnum::USER->value)->orWhere('level',LevelUserEnum::ADMIN->value)->chunk(1000, function ($users) use ($community) {
                $userIds = $users->pluck('id')->toArray();
                $community->users()->syncWithoutDetaching($userIds);
            });
        }


        if($community->is_global_seller){
            User::where('level',LevelUserEnum::SELLER->value)->orWhere('level',LevelUserEnum::RESTAURANT->value)->chunk(1000, function ($users) use ($community) {
                $userIds = $users->pluck('id')->toArray();
                $community->users()->syncWithoutDetaching($userIds);
            });
        }

        if($community->manager_id!=null){
           $user= $community->manager;
           $community->users()->syncWithPivotValues([$user->id],['is_manager'=>true,]);
        }elseif (auth()->check()){
            $community->users()->syncWithPivotValues([auth()->id()],['is_manager'=>true,]);
        }



    }

    /**
     * Handle the Community "updated" event.
     */
    public function updated(Community $community): void
    {
        if($community->is_global && !$community->getOriginal('is_global')){
            User::where('level',LevelUserEnum::SELLER->value)->orWhere('level',LevelUserEnum::RESTAURANT->value)->chunk(1000, function ($users) use ($community) {
                $userIds = $users->pluck('id')->toArray();
                $community->users()->syncWithoutDetaching($userIds);
            });
        }
        if($community->is_global_seller && !$community->getOriginal('is_global_seller')){
            User::where('level',LevelUserEnum::SELLER->value)->orWhere('level',LevelUserEnum::RESTAURANT->value)->chunk(1000, function ($users) use ($community) {
                $userIds = $users->pluck('id')->toArray();
                $community->users()->syncWithoutDetaching($userIds);
            });
        }
    }

    /**
     * Handle the Community "deleted" event.
     */
    public function deleted(Community $community): void
    {
        //
    }

    /**
     * Handle the Community "restored" event.
     */
    public function restored(Community $community): void
    {
        //
    }

    /**
     * Handle the Community "force deleted" event.
     */
    public function forceDeleted(Community $community): void
    {
        //
    }
}
