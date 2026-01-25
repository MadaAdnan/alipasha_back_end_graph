<?php

namespace App\Service;

use Http;

class SmsService
{
    protected $username = "_JAJBN";
    protected $password = "zysz3wozensjbo";
    protected $apiUrl = 'https://api.sms-gate.app/3rdparty/v1/message';


    public function sendSms(array $phoneNumbers, string $message): array
    {

        $url = $this->apiUrl;
        $username = $this->username;
        $password = $this->password;

        foreach ($phoneNumbers as $key => $phoneNumber) {
            if (\Str::startsWith($phoneNumber, '+') || \Str::startsWith($phoneNumber, '00')) {
                continue;
            } else {
                $phoneNumbers[$key] = '+' . $phoneNumber;

            }
        }
        $payload = json_encode([
            'message' => $message,
            'phoneNumbers' => $phoneNumbers
        ]);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode("$username:$password"),
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return [
                'status' => false,
                'error' => $error,
                'http_code' => $httpCode
            ];
        }

        curl_close($ch);

        return [
            'status' => $httpCode >= 200 && $httpCode < 300,
            'response' => json_decode($response, true),
            'http_code' => $httpCode
        ];
    }
}
