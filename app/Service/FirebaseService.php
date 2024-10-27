<?php

namespace App\Service;

use Kreait\Firebase\Factory;
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

    public function sendNotificationToMultipleTokens($deviceTokens, $title, $body)
    {
      /*  $message = CloudMessage::withTarget('token', $deviceTokens)
            ->withNotification(['title' => 'My title', 'body' => 'My Body']);

        $this->messaging->send($message);

return;*/
//        ////////
        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body));

        $responses = [];

        foreach ($deviceTokens as $token) {
            $response = $this->messaging->send($message->withChangedTarget(MessageTarget::TOKEN, $token));
            $responses[] = $response;
        }

        return $responses;
    }
}
