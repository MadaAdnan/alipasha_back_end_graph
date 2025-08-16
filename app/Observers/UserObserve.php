<?php

namespace App\Observers;

use App\Enums\PlansDurationEnum;
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
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        try {
            $setting = Setting::first();
            if ($setting->send_via_email) {
                $job = new SendEmailJob([$user], new RegisteredEmail($user));
                dispatch($job);
            }


            $message="أهلا بك في تطبيق علي باشا\nكود التحقق الخاص بك\n{$user->code_verified}";
            $phone = $user->phone_code . $user->phone;
            if (!empty($phone) && $setting->send_via_whatsapp) {
                $smsJob = new SMsJob($phone, $message);
                dispatch($smsJob);
            }

        } catch (\Exception|\Error $e) {
        }
        $plan = Plan::where('duration', PlansDurationEnum::FREE->value)->first();
        if ($plan) {
            $user->plans()->syncWithPivotValues([$plan->id], ['subscription_date' => now(), 'expired_date' => now()->addYear()]);
        }
        $groups = Community::where('is_global', true)->pluck('id')->toArray();
        $user->communities()->syncWithoutDetaching($groups);
        if ($user->user_id != null) {
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
