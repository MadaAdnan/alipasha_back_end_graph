<?php

namespace App\Service;

use Http;

class SmsService
{
    protected $username="_JAJBN";
    protected $password="dysqfnnhg8g2qp";
    protected $apiUrl = 'https://api.sms-gate.app/3rdparty/v1/message';



    public function sendSms(array $phoneNumbers, string $message): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode("{$this->username}:{$this->password}")
        ])

            ->post($this->apiUrl, [
                'message' => $message,
                'phoneNumbers' => $phoneNumbers
            ]);

        return [
            'status' => $response->successful(),
            'response' => $response->json()
        ];
    }
}
