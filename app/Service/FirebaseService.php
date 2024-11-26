<?php

namespace App\Service;

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
            ->withServiceAccount(storage_path('app/firebase/fcm.json'));

        $this->messaging = $factory->createMessaging();
    }

    public function sendNotificationToMultipleTokens($deviceTokens, $data)
    {
        $config = AndroidConfig::fromArray([
            'ttl' => '3600s',
            'priority' => 'normal',

            'notification' => [
                'title' => $data['title'],
                'body' => $data['body'],
                'icon' => 'stock_ticker_update',
                'color' => '#f45342',
                'sound' => 'default',
                'tag' => 'grouped_notification',
            ],

        ]);
        $message = CloudMessage::new()
            ->withAndroidConfig($config)
            ->withNotification(Notification::create($data['title'], $data['body']));

        $responses = [];

        foreach ($deviceTokens as $token) {
            $response = $this->messaging->send($message->withChangedTarget(MessageTarget::TOKEN, $token));
            $responses[] = $response;
        }

        return $responses;
    }
}
