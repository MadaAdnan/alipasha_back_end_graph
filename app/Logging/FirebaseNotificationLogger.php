<?php

namespace App\Logging;
use Illuminate\Support\Facades\Log;
class FirebaseNotificationLogger
{
    protected string $logChannel = 'firebase';

    public function logSuccess(string $token, array $data)
    {
        Log::channel($this->logChannel)->info('🔔 إشعار مُرسل بنجاح', [
            'device_token' => $token,
            'title' => $data['title'] ?? '',
            'body' => $data['body'] ?? '',
            'url' => $data['url'] ?? '',
            'timestamp' => now()->toDateTimeString()
        ]);
    }

    public function logFailure(string $token, string $error)
    {
        Log::channel($this->logChannel)->error('❌ فشل إرسال الإشعار', [
            'device_token' => $token,
            'error_message' => $error,
            'timestamp' => now()->toDateTimeString()
        ]);
    }
}
