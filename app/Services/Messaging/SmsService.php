<?php

namespace App\Services\Messaging;

use Illuminate\Support\Facades\Http;

class SmsService
{
    public function sendFlowSms(string $mobile, string $templateId, array $parameters = []): array
    {
        $authKey = config('services.msg91.auth_key');
        $senderId = config('services.msg91.sender_id');
        $route = config('services.msg91.route', '4');
        $country = config('services.msg91.country', '91');
        $flowUrl = config('services.msg91.flow_url', 'https://control.msg91.com/api/v5/flow/');

        if (!$authKey || !$templateId) {
            return [
                'ok' => false,
                'message' => 'SMS gateway is not configured.',
            ];
        }

        $payload = [
            'sender' => $senderId,
            'route' => $route,
            'country' => $country,
            'mobile' => $mobile,
            'template_id' => $templateId,
            'variables' => $parameters,
        ];

        $response = Http::withOptions([
            'verify' => (bool) config('services.msg91.verify_ssl', false),
        ])->withHeaders([
            'authkey' => $authKey,
            'accept' => 'application/json',
        ])->post($flowUrl, $payload);

        if (!$response->successful()) {
            return [
                'ok' => false,
                'message' => 'Failed to send SMS.',
            ];
        }

        return [
            'ok' => true,
            'message' => 'SMS sent successfully.',
        ];
    }
}