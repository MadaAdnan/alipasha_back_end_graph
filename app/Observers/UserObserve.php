<?php

namespace App\Observers;

use App\Enums\PlansDurationEnum;
use App\Events\CreatedUserEvent;
use App\Helpers\GlobalHelper;
use App\Helpers\StrHelper;
use App\Jobs\SendEmailJob;
use App\Jobs\SmsJob;
use App\Jobs\WebhokUserJob;
use App\Mail\RegisteredEmail;
use App\Models\Community;
use App\Models\Plan;
use App\Models\Point;
use App\Models\Setting;
use App\Models\User;
use App\Service\SmsService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class UserObserve
{
    public function creating(User $user): void
    {
        $user->affiliate = StrHelper::getAfflieate();
        $user->is_sync_webhok = false;
        $setting = Setting::first();
        if ($setting->is_active_register_win) {
            $user->register_win_amount = $setting->register_win_amount;
        }
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        event(new CreatedUserEvent($user));



    }


    public function updating(User $user): void
    {
        if ($user->affiliate == null) {
            $user->affiliate = StrHelper::getAfflieate();
        }
        $user->is_sync_webhok = false;

    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if (\Str::isUrl($user->url_webhok)) {
            dispatch(new WebhokUserJob($user));
        }
        if ($user->email_verified_at != null && $user->getOriginal('email_verified_at') == null && $user->user_id != null) {
            $setting = Setting::first();
            if ($setting->active_points) {
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
        $oldType = $user->getOriginal('is_seller');
        $newType = $user->is_seller;
        if ($oldType == false && $newType == true) {
            $community = \App\Models\Community::where('is_global_seller', true)->first();
            $community->users()->syncWithoutDetaching([$user->id]);
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
