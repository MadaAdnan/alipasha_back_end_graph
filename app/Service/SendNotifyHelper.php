<?php

namespace App\Service;

use App\Jobs\SendFirebaseNotificationJob;
use App\Jobs\SendNotificationJob;
use App\Models\User;

class SendNotifyHelper
{
    public static function sendNotify(User $user, $data)
    {

        // إرسال الإشعار
        try {
        $job=new SendNotificationJob($user, $data);
         dispatch($job);
        } catch (\Exception | \Error $e) {
            \Log::alert('SendNotifyHelper DataBase'.$e->getMessage());
        }
        if ($user->device_token != null) {
            try {

                $job = new SendFirebaseNotificationJob([$user->device_token], $data);
                dispatch($job);

            } catch (\Exception | \Error $e) {
                \Log::alert('SendNotifyHelper FireBase'.$e->getMessage());
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
        $tokens=$users->whereNotNull('device_token')->pluck('device_token')->toArray();

            try {
                $job = new SendFirebaseNotificationJob($tokens, $data);
                dispatch($job);
            } catch (\Exception | \Error $e) {

            }


    }
}
