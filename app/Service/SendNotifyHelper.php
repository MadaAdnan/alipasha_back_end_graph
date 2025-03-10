<?php

namespace App\Service;

use App\Jobs\SendFirebaseNotificationJob;
use App\Jobs\SendNotificationJob;
use App\Models\User;

class SendNotifyHelper
{
    public static function sendNotify(User $user, $data)
    {
        \Log::info(' ---JOB NOTIFICATION--');
        // إرسال الإشعار
        try {
        $job=new SendNotificationJob($user, $data);
         dispatch($job);
        } catch (\Exception | \Error $e) {

        }
        if ($user->device_token != null) {
            try {
                $job = new SendFirebaseNotificationJob([$user->device_token], $data);
                dispatch($job);
            } catch (\Exception | \Error $e) {

            }
        }

    }

    public static function sendNotifyMultiUser($users, $data)
    {

        // إرسال الإشعار
        try {
            SendNotificationJob::dispatch($users, $data);
        } catch (\Exception | \Error $e) {

        }
        $tokens=$users->whereNotNull('device_token')->pluk('device_token')->toArray();

            try {
                $job = new SendFirebaseNotificationJob($tokens, $data);
                dispatch($job);
            } catch (\Exception | \Error $e) {

            }


    }
}
