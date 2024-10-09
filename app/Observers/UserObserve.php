<?php

namespace App\Observers;

use App\Enums\PlansDurationEnum;
use App\Mail\RegisteredEmail;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class UserObserve
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //Mail::to($user)->send(new RegisteredEmail($user));
        $plan=Plan::where('duration',PlansDurationEnum::FREE->value)->first();
        if($plan){
            $user->plans()->sync([$plan->id]);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
