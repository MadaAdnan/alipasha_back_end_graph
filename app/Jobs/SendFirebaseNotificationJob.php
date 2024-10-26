<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFirebaseNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $ids;
    private $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $ids,array $data)
    {
        //
        $this->ids = $ids;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        // مفتاح الخادم (Server Key) من Firebase Console
        $serverKey = 'YOUR_SERVER_KEY'; // ضع مفتاح الخادم الخاص بك هنا

        // بيانات الإشعار
        $notification = [
            'title' => $this->data['title'],  // عنوان الإشعار
            'body' => $this->data['body'],    // نص الإشعار
            'sound' => 'default'        // صوت الإشعار
        ];
        if (!isset($data['url'])) {
            $data['url'] = 'https://ali-pasha.com';
        }
        // إعداد البيانات المطلوبة للإرسال
        $fields = [
            'registration_ids' => $this->ids,      // جهاز مستهدف (يمكن استخدام "to" أو "topic")
            'notification' => $notification,
            'data' => ['url' => $this->data['url']],             // يمكنك إضافة بيانات إضافية هنا إذا كنت تريد
        ];

        // إعداد الرؤوس (Headers)
        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json',
        ];

        // تهيئة الاتصال باستخدام cURL
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // تنفيذ الطلب وإرجاع النتيجة
        $result = curl_exec($ch);

        // التحقق من أي أخطاء
        if ($result === false) {
            die('خطأ في إرسال الإشعار: ' . curl_error($ch));
        }

        // إغلاق الاتصال
        curl_close($ch);

    }
}
