<?php

namespace App\Listeners;

use App\Events\CreatedUserEvent;
use App\Helpers\PlanHelpers;
use App\Jobs\SendEmailJob;
use App\Jobs\SmsJob;
use App\Mail\RegisteredEmail;
use App\Models\Point;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CompleteCreatedUserListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CreatedUserEvent $event): void
    {
       $user=$event->user;
        PlanHelpers::RegisterToPlanFree($user);
        PlanHelpers::RegisterToGlobalCommunity($user);
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


            if ($user->user_id != null) {

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
        } catch (\Exception|\Error $e) {
            \Log::error("UserObserver error: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

        }
    }
}
