<?php

namespace App\Services\Messaging;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function sendFlowSms(string $mobile, string $templateId, array $parameters = []): array
    {
        $authKey = config('services.msg91.auth_key');
        $country = config('services.msg91.country', '91');
        $flowUrl = config('services.msg91.flow_url', 'https://control.msg91.com/api/v5/flow/');

        if (!$authKey || !$templateId) {
            Log::error('MSG91 Flow SMS configuration is incomplete.', [
                'auth_key_configured' => filled($authKey),
                'template_configured' => filled($templateId),
            ]);
            return [
                'ok' => false,
                'message' => 'SMS gateway is not configured.',
            ];
        }

        // MSG91 Flow API v5 requires the mobile (with country code) inside a "recipients" array, not a top-level field
        $mobileWithCountryCode = str_starts_with($mobile, $country) ? $mobile : $country . $mobile;

        $payload = [
            'template_id' => $templateId,
            'recipients' => [
                array_merge(['mobiles' => $mobileWithCountryCode], $parameters),
            ],
        ];

        $response = Http::withOptions([
            'verify' => (bool) config('services.msg91.verify_ssl', false),
        ])->withHeaders([
            'authkey' => $authKey,
            'accept' => 'application/json',
        ])->post($flowUrl, $payload);

        $body = $response->json();

        Log::info('MSG91 Flow SMS response received.', [
            'http_status' => $response->status(),
            'successful' => $response->successful(),
            'response_type' => is_array($body) ? ($body['type'] ?? null) : null,
            'response_message' => is_array($body) ? ($body['message'] ?? null) : null,
        ]);

        if (!$response->successful() || (is_array($body) && isset($body['type']) && strtolower((string) $body['type']) !== 'success')) {
            return [
                'ok' => false,
                'message' => is_array($body) && isset($body['message']) ? $body['message'] : 'Failed to send SMS.',
            ];
        }

        return [
            'ok' => true,
            'message' => 'SMS sent successfully.',
        ];
    }
}