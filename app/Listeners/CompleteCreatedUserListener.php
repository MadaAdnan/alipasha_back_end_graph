<?php

namespace App\Listeners;

use App\Events\CreatedUserEvent;
use App\Helpers\GlobalHelper;
use App\Jobs\SendEmailJob;
use App\Jobs\SmsJob;
use App\Mail\RegisteredEmail;
use App\Models\Point;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/*class CompleteCreatedUserListener
{

    public function __construct()
    {
        //
    }


    public function handle(CreatedUserEvent $event): void
    {
        $user = $event->user;
        GlobalHelper::RegisterToPlanFree($user);
        GlobalHelper::RegisterToGlobalCommunity($user);

        $setting = Setting::first();
        if ($setting->is_active_register_win && $setting->register_win_amount > 0) {
            GlobalHelper::addRegisterWinToUser($user, $setting->register_win_amount);
        }
        try {

            if ($setting->send_via_email) {
                $job = new SendEmailJob([$user], new RegisteredEmail($user));
                dispatch($job);
            }


            $message = "أهلا بك في تطبيق علي باشا\nكود التحقق الخاص بك\n{$user->code_verified}";
            $phone = $user->phone_code . $user->phone;
            if (!empty($phone) && $setting->send_via_whatsapp) {
                $smsJob = new SMsJob($phone, $message);
                dispatch($smsJob);
            }


            if ($user->user_id != null) {

                if ($setting->active_points) {

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
}*/

class CompleteCreatedUserListener
{
    public function __construct()
    {
        //
    }

    public function handle(CreatedUserEvent $event): void
    {
        $user = $event->user;
        try {
            $setting = Setting::first();

            // تسجيل المستخدم في خطة مجانية
            try {
                GlobalHelper::RegisterToPlanFree($user);
            } catch (\Throwable $e) {
                \Log::error("RegisterToPlanFree error: " . $e->getMessage());
            }

            // تسجيل المستخدم في المجتمع العام
            try {
                GlobalHelper::RegisterToGlobalCommunity($user);
            } catch (\Throwable $e) {
                \Log::error("RegisterToGlobalCommunity error: " . $e->getMessage());
            }

            // إضافة مكافأة تسجيل
            try {
                if ($setting->is_active_register_win && $setting->register_win_amount > 0) {
                    GlobalHelper::addRegisterWinToUser($user, $setting->register_win_amount);
                }
            } catch (\Throwable $e) {
                \Log::error("addRegisterWinToUser error: " . $e->getMessage());
            }

            // إرسال بريد ترحيبي
            try {
                if ($setting->send_via_email) {
                    dispatch(new SendEmailJob([$user], new RegisteredEmail($user)));
                }
            } catch (\Throwable $e) {
                \Log::error("SendEmailJob error: " . $e->getMessage());
            }

            // إرسال رسالة واتساب
            try {
                $phone = $user->phone_code . $user->phone;
                if (!empty($phone) && $setting->send_via_whatsapp) {
                    $smsJob = new SMsJob($phone, "أهلا بك في تطبيق علي باشا\nكود التحقق الخاص بك\n{$user->code_verified}");
                    dispatch($smsJob);
                }
            } catch (\Throwable $e) {
                \Log::error("SMsJob error: " . $e->getMessage());
            }

            // إضافة نقاط للمُعرّف (affiliate)
            try {
                if ($user->user_id && $setting->active_points) {
                    $delegate = $user->user;

                    Point::create([
                        'user_id' => $delegate->id,
                        'credit' => $setting->num_point_for_register,
                        'debit' => 0,
                        'info' => 'ربح من تسجيل المستخدم ' . $user->name,
                    ]);
                }
            } catch (\Throwable $e) {
                \Log::error("Point create error: " . $e->getMessage());
            }

        } catch (\Throwable $e) {
            // في حالة خطأ غير متوقع
            \Log::error("General CreatedUserListener error: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}

