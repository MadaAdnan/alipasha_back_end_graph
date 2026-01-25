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
            $community->users()->syncWithPivotValues([auth()->id()],['notify'=>1],false);
          //  event(new CreateCommunityEvent($community));
        }catch (Exception | \Error $e){}

        if($community->is_global){
            User::whereIn('level', [LevelUserEnum::USER->value, LevelUserEnum::ADMIN->value])
                ->select('id') // تحديد الأعمدة المطلوبة فقط
                ->chunk(1000, function ($userIds) use ($community) {
                    $community->users()->syncWithPivotValues($userIds->pluck('id')->toArray(),['notify'=>1],false);
                });

        }


        if($community->is_global_seller){
            User::whereIn('level', [LevelUserEnum::SELLER->value, LevelUserEnum::RESTAURANT->value])
                ->select('id') // تحديد الأعمدة المطلوبة فقط
                ->chunk(1000, function ($userIds) use ($community) {
                    $community->users()->syncWithPivotValues($userIds->pluck('id')->toArray(),['notify'=>1],false);
                });

        }

        if($community->manager_id!=null){
           $user= $community->manager;
           $community->users()->syncWithPivotValues([$user->id],['is_manager'=>true,'notify'=>1],false);
        }elseif (auth()->check()){
            $community->users()->syncWithPivotValues([auth()->id()],['is_manager'=>true,'notify'=>1],false);
        }



    }

    /**
     * Handle the Community "updated" event.
     */
    public function updated(Community $community): void
    {
        if($community->is_global && !$community->getOriginal('is_global')){
            User::whereIn('level', [LevelUserEnum::USER->value, LevelUserEnum::ADMIN->value])
                ->select('id') // تحديد الأعمدة المطلوبة فقط
                ->chunk(1000, function ($userIds) use ($community) {
                    $community->users()->syncWithPivotValues($userIds->pluck('id')->toArray(),['notify'=>1],false);
                });
        }
        if($community->is_global_seller && !$community->getOriginal('is_global_seller')){
            User::whereIn('level', [LevelUserEnum::SELLER->value, LevelUserEnum::RESTAURANT->value])
                ->select('id') // تحديد الأعمدة المطلوبة فقط
                ->chunk(1000, function ($userIds) use ($community) {
                    $community->users()->syncWithPivotValues($userIds->pluck('id')->toArray(),['notify'=>1],false);
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
