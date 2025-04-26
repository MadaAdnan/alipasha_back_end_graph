<?php

namespace App\Service;

use App\Logging\FirebaseNotificationLogger;
use App\Models\User;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MessageTarget;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\ServiceAccount;

class FirebaseService

{
    protected $messaging;

    public function __construct()
    {
        // storage_path('app/firebase/fcm.json')

        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/firebase/fcm.json'));

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
           // $response = $this->messaging->send($message->withChangedTarget(MessageTarget::TOKEN, $token));
            try {
            $response = $this->messaging->send($message->withChangedTarget(MessageTarget::TOKEN, $token));
                $logger->logSuccess($token, $data);
            } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
                User::where('device_token',$token)->update(['device_token'=>null]);
                $logger->logFailure($token, $e->getMessage());
            }
            $responses[] = $response;
        }
        \Log::alert('Finish');
        return $responses;
    }
}
