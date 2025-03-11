<?php

namespace App\Observers;

use App\Enums\PlansDurationEnum;
use App\Helpers\StrHelper;
use App\Jobs\SendEmailJob;
use App\Mail\RegisteredEmail;
use App\Models\Community;
use App\Models\Plan;
use App\Models\Point;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class UserObserve
{
    public function creating(User $user): void
    {
        $user->affiliate = StrHelper::getAfflieate();
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        try {

            $job = new SendEmailJob([$user], new RegisteredEmail($user));
            dispatch($job);
        } catch (\Exception | \Error $e) {
        }
        $plan = Plan::where('duration', PlansDurationEnum::FREE->value)->first();
        if ($plan) {
            $user->plans()->syncWithPivotValues([$plan->id],['subscription_date'=>now(),'expired_date'=>now()->addYear()]);
        }
        $groups = Community::where('is_global', true)->pluck('id')->toArray();
        $user->communities()->syncWithoutDetaching($groups);
        if($user->user_id!=null){
            $setting = Setting::first();
            if ( $setting->active_points) {
                /**
                 * @var $delegate User
                 */
                $delegate = $user->user;

                Point::create([
                    'user_id' => $delegate->id,
                    'credit' => $setting->num_point_for_register,
                    'debit' => 0,
                    'info' => 'ربح من تسجيل المستخدم ' . $user->name,
                ]);
            }
        }
    }


    public function updating(User $user): void
    {
        if ($user->affiliate == null) {
            $user->affiliate = StrHelper::getAfflieate();
        }

    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if ($user->email_verified_at != null && $user->getOriginal('email_verified_at') == null && $user->user_id != null) {
            $setting = Setting::first();
            if ( $setting->active_points) {
                /**
                 * @var $delegate User
                 */
                $delegate = $user->user;

                Point::create([
                    'user_id' => $delegate->id,
                    'credit' => $setting->num_point_for_register,
                    'debit' => 0,
                    'info' => 'ربح من تسجيل المستخدم ' . $user->name,
                ]);
            }
        }
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
