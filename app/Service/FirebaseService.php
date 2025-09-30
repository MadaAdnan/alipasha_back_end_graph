<?php

namespace App\Service;

use App\Logging\FirebaseNotificationLogger;
use App\Models\User;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MessageTarget;
use Kreait\Firebase\Messaging\Notification;


class FirebaseService

{
    protected $messaging;

    public function __construct()
    {


        $factory = (new Factory)
            ->withServiceAccount([
                "type" => "service_account",
                "project_id" => "peaceful-nature-376111",
                "private_key_id" => "7845143839da5540b877f54ceb23fc50a8239d47",
                "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDTto5NW4SOiypR\ntRbRgpZ5QbH/r/LYFMYj2ZaPp/ys+b+Nj3Rc1xVVffnyUtK+WBAIiY5xoB3aMXZe\n27INOlOqoaD+d5eAtOTGy3zQgnuyIeP/7GHxW0RuZ+RzjjeJ2LmPkN8XYA/a59lE\nSDa4v5B4qztifm0elPZXDonVFdvHFbeiQ1LKfhvaVxU3qCpO4ZpTksKhxEwZ+4p2\nWo0ZqeHNndgJpdWfA8otA9ygqxNhoH81GU0R7zaEbKCfAvRcF/v4/DnhNHMn5raC\nOxYDjHTs3j4Gd1g6J/IAqRPRt/sYBgEA1MJJb1amDbQLt76EVCG96b1HsBQNQy02\ns0U6/BB/AgMBAAECgf9S+Y7MGBIxIQR1AYRFQz5g8l57giSrNiPHPoAZIr2PuFJa\n04kc2dnX/TJOHCfRx1yBUWgtID6S8CUfCFEViMaXGWxU+d9m537Cav4quk5rLN0v\nGyCdIigFHV1r7KaCYBkEgMLuu7d7FA29tTpn2KxxSVWnmeUqbIzKPJMd8HMyku7v\nnCXwikQV+HQE5uPobFPftVbsMyY0zuGemsYZjOi+IwbEJyuO1RRv+6uiy7icGHy5\nqlavtqba9EUZNhoU86CbSy4WgKoqLIHFyYVK37ViK42CRyN5R8dFw4HSoK6BX6kg\nYv/0Rxjdde1lr10vf8q/sQ554sE2zpG650UQOpkCgYEA9r7hasns0uuoNH5twZQ/\nhc5tAVYlCiSocm90LaeJ/Y9dI0W7xOJ8p3A6NFULva45fUzAmI8UcdvTuxP5k5cx\nT2pHh0jjjXNSt4EdL4LynUAo/yuHoRzWnbTJq+KRRyIBYTLhu/uEsb35WAgV7goq\nRkXiqABBawuHK2eVkkrI4GMCgYEA26dP1L1SrItsA57OoH8mKCyalE9ZVVWKJxhe\n/YrP7AAMkHD1SMGf37XSvNQ2qbdFlzOmyboBBz9KVNLzmrSa3wNs8xwaPwGI7UfO\n6AAFALFFJHVPBSTA86E9hegA2DFpaPJmXryfq1ifhTNmCcp1m+Y1gbb+p+bJUTx5\njZngtDUCgYEA51IMAhFL9HD5QD1GqDRTWR4tExOvpdK4GJBkWmi29P9yUZ8OaYXr\nVw3fgzA30ZuESfOqm1uHzzZHSRtw3uzfTZRVen7a1cFHysFAm0ooKUP9Kzuj62cd\nzLuwVXQSvp0irf0FozYpMHjpSZ5K8N6Ww0rBMr8KlVA5aHezwXJa4AECgYEAtPZ1\nzbYga6j910m5v/6hCA4fWDq7RZOQmz7323jGbg9oSoPVgRiMR3l74jgPxOkJ+1UB\nf0av3DGnYpTdP8K1qTAgTXYEpY31Kk+zxe1mMbjaVvwSmxE5qj5X+n82WubyihTS\nhBBwl3EPE2VSFpTnKgaSZIjH6ro3d6cUGbYBq1UCgYEA6xNYwFpuFaAvEDdPwwEb\nEUgkXWMUU4VRmE8Lgm7BYLFNnpxaPi9QZdOBLueiHmCpPcK90CbpkV3TJCqE890S\nCRNvfUZzzxlMNc9tdg3Zyu9knq2vDOkNT2nJa9q+c0wXt+7pgQ8Xx1tbc628pvky\nzFWqGveB+IniV/LyQGt4vjk=\n-----END PRIVATE KEY-----\n",
                "client_email" => "firebase-adminsdk-4eb52@peaceful-nature-376111.iam.gserviceaccount.com",
                "client_id" => "105713725892717912503",
                "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                "token_uri" => "https://oauth2.googleapis.com/token",
                "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-4eb52%40peaceful-nature-376111.iam.gserviceaccount.com",
                "universe_domain" => "googleapis.com"
            ]);

        $this->messaging = $factory->createMessaging();
    }

    /* public function sendNotificationToMultipleTokens($deviceTokens, $data)
     {
         $logger = new FirebaseNotificationLogger();
         $config = AndroidConfig::fromArray([
             'ttl' => '3600s',
             'priority' => 'high',
             'notification' => [
                 'title' => $data['title'],
                 'body' => $data['body'],
                 'icon' => 'stock_ticker_update',
                 'color' => '#f45342',
                 'sound' => 'default',
                 'tag' => 'grouped_notification',
                 'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
             ],

         ]);
         $message = CloudMessage::new()
             ->withAndroidConfig($config)
             ->withNotification(Notification::create($data['title'], $data['body']));

         $responses = [];

         foreach ($deviceTokens as $token) {
             try {
                 $response = $this->messaging->send($message->withChangedTarget(MessageTarget::TOKEN, $token));
                 $logger->logSuccess($token, $data);
             } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
                 User::where('device_token', $token)->update(['device_token' => null]);
                 $logger->logFailure($token, $e->getMessage());
             }
             $responses[] = $response;
         }

         return $responses;
     }*/
    public function sendNotificationToMultipleTokens(array $deviceTokens, array $data)
    {
//        $logger = new FirebaseNotificationLogger();

        // تصفية التوكينات: إزالة الفارغ والمكرر
        $tokens = collect($deviceTokens)
            ->filter(fn($t) => !empty($t) && is_string($t))
            ->unique()
            ->values()
            ->toArray();
        \Log::warning("tokens " . count($tokens));
        if (empty($tokens)) {
            \Log::warning('لم يتم العثور على توكينات صالحة للإرسال');
            return [];
        }

        // إعداد الرسالة
        $config = AndroidConfig::fromArray([
            'ttl' => '3600s',
            'priority' => 'high',
            'notification' => [
                'title' => $data['title'] ?? 'بدون عنوان',
                'body' => $data['body'] ?? '',
                'icon' => 'stock_ticker_update',
                'color' => '#f45342',
                'sound' => 'default',
                'tag' => 'grouped_notification',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
            ],
        ]);

        $message = CloudMessage::new()
            ->withAndroidConfig($config)
            ->withNotification(Notification::create($data['title'] ?? '', $data['body'] ?? ''));

        try {
            // إرسال متعدد
            /**
             * @var $response \Kreait\Firebase\Messaging\MulticastSendReport
             */
            $response = $this->messaging->send($message, $tokens);
            \Log::info("تم إرسال إشعارات", [
                'total' => count($tokens),
                'success' => $response->successes()->count(),
                'failure' => $response->failures()->count(),
            ]);

            return $response;
        } catch (\Throwable $e) {
            \Log::error('فشل إرسال الإشعارات: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return [];
        }
    }

}
