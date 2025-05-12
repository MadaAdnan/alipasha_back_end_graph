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
                "project_id" => "alipasha-e8c82",
                "private_key_id" => "5bf17b5185905e8d7aacb5c611eb8927cd186a36",
                "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC5UIahJZp/5ABU\nFmHyY9Kwo4GZqfpi1O4oqsnD9wjHx7Ttf0EHZKDJ9+vpPPBgFaGXhfVpxl+4xHEY\nZb7fyuY4tNEu/R876F7AlOj1VyhpPuSkRkbWQ0ih5swfnmWZrzhCc0NX3Hd/Mcmc\nVRyBg/KEu2dBzYI7Z0ipzgB8txTyPSc/HHbExZThNGfKbzGjsJ5iSP82D7QazXFv\nEb1mrmLCMO96qsKG+OC0ep1r4Gjfo7ICVm5PzwXS9GMm1oTwRMfL85s6k4jOBpKj\nBtCWOzyukkogmAMA0Iy1cUg/27VbZYM+JMoIPESDmOZnre9Pe632UiWt1oLmtlgO\nBBDnGE0rAgMBAAECggEAT+FwRyKIeLx1j8meKvfwqAezI+PrdJLInmsQUhK4jDV/\nvihuhZtQ2/6siHumOiTY5RHvecrzFBhJj/S71IgHuUXoLdMalZ5Sybzmtmr9lnBv\n6ogInO+f7XopXy/OJ/Mp0ysQSl1UfVWezG67vPB9p82Icwr9KlIZNmkAOMJKmygZ\nMxd1kqWNGWwTgGLixAyZI4AK3misIuByLNqapKm735emIpa6RwD/B/m3PuzSk0gw\nurcBbUT+R4E7AUAyTGrSrJJqDga8xiZj6hbaqSw1XG9x8JsD5VGmCIvEa77f7C7n\nWEI+ApbAICCK+B0E/78u+foF33VDknd7OpMEs2t+oQKBgQDk196hw04kL6fiA/+Z\nhqLvPoJ3oE91Yk+dALMxKWG/anvUweE0iqAohDt5R6m9Ko+bEk6+0kdV4+eWHf5g\nsGXb+C+0QnpCe3e102rMHMJzvA/S6eHpbF1m1Q8xWl1c1UhK8wXEfJVxXkDvF/Rs\nu7d65V1+VypuKx1Vtf6qAVgJCQKBgQDPTkdp39rMNdxeOYBB+vM2ZcD/nYxjOe1W\nKxYcdTnt6RC9wyDU2kMY7M0Fprkc6IHUZYeVpKfcelGFqIXmQp2OefzcJSppo069\nV/LS+lfLbKhJbqtUbNXIt8mWE4Q4j9bUunXCmz/bQeab+qtYYLpVYeNPeodxeadG\ngd3vRpp1kwKBgQDf7Fts7i8IOZmND1yMXbIRaJlBdRxLQGemuYieEmATbZ6+EPjM\n8NjGzJ1ljzoIYB0lIETtc9VZtihmO/MtqW4CUFhdiq8XXrlEshtOntWnad4SA8mL\nHv5GITU8la/Fpu2WaTa7jSyuQgxH6KjDvOtM1iMl/SUHqCiMNgl7UNVUAQKBgHrF\nBQ3ygIVHsIYsz4RDth2VDUNUbnulJqVNRv77fZ8j08JN+PeVev8b3h9mqWIxYBIF\nPtKkfLTZy8W4E/RVpzFllRZa/E8rY8pGd4vyrmPOqaszW0vYWJNSJJQ4YqmMpdG9\ny1fA0YRr9sKRJUBlqHRAUHoVOnk9bo8XAfRef8L5AoGAP7O89ITmZhEpjDBoLVc0\nwhWJ+I6Fn6D+4ve8O7HdL3Q77tjCEoI/ORHCdem04KCpY2/dodcFKrOybzwusdpz\nzfTeXY/Yh4l7xTe+u4lVKyEKbBwpqP6GqBio95nnPWnBKjhGAiQ69JziFREfxmeQ\nNDMAcO+6067mUz+ZH3xETY8=\n-----END PRIVATE KEY-----\n",
                "client_email" => "firebase-adminsdk-noa7x@alipasha-e8c82.iam.gserviceaccount.com",
                "client_id" => "116359292799425670101",
                "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                "token_uri" => "https://oauth2.googleapis.com/token",
                "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-noa7x%40alipasha-e8c82.iam.gserviceaccount.com",
                "universe_domain" => "googleapis.com"
            ]);

        $this->messaging = $factory->createMessaging();
    }

    public function sendNotificationToMultipleTokens($deviceTokens, $data)
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
    }
}
