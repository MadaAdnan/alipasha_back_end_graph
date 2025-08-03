<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Helpers\StrHelper;
use App\Jobs\SendEmailJob;
use App\Jobs\SmsJob;
use App\Mail\RegisteredEmail;
use App\Models\Setting;

final class ResendEmailVerify
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $user = auth()->user();
        $user->update(['code_verified' => StrHelper::generateDigits(6)]);

       // \Mail::to($user)->send(new RegisteredEmail($user));
        try {
            $setting = Setting::first();
            if ($setting->send_via_email) {
                $job = new SendEmailJob([$user], new RegisteredEmail($user));
                dispatch($job);
            }


            $message = "أهلا بك في تطبيق علي باشا \n
            كود التحقق الخاص بك هو \n {$user->code_verified}";
            $phone = $user->phone_code . $user->phone;
            if (!empty($phone) && $setting->send_via_whatsapp) {
                $smsJob = new SMsJob($phone, $message);
                dispatch($smsJob);
            }

        } catch (\Exception|\Error $e) {
        }
        return $user;
    }
}
