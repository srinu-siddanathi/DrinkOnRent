<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        return $this->issueOtp($request, false);
    }

    public function resendOtp(Request $request)
    {
        return $this->issueOtp($request, true);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|size:10',
            'otp' => 'required|string|size:6',
        ]);

        $verification = $this->verifyOtpWithMsg91($request->phone, $request->otp);

        if (!$verification['ok']) {
            throw ValidationException::withMessages([
                'otp' => [$verification['message']],
            ]);
        }

        $customer = Customer::firstOrCreate(
            ['phone' => $request->phone],
            ['is_phone_verified' => true]
        );

        return response()->json([
            'message' => 'OTP verified successfully',
            'token' => $customer->createToken('auth-token')->plainTextToken,
            'customer' => $customer,
        ]);
    }

    private function issueOtp(Request $request, bool $isResend)
    {
        $request->validate([
            'phone' => 'required|string|size:10',
        ]);

        if (!Customer::where('phone', $request->phone)->exists()) {
            return response()->json([
                'message' => 'This number is not registered with us.',
            ], 404);
        }

        $sent = $isResend
            ? $this->resendOtpWithMsg91($request->phone)
            : $this->sendOtpWithMsg91($request->phone);

        if (!$sent['ok']) {
            return response()->json([
                'message' => $sent['message'],
            ], 502);
        }

        return response()->json([
            'message' => $isResend ? 'OTP resent successfully' : 'OTP sent successfully',
        ]);
    }

    private function sendOtpWithMsg91(string $phone): array
    {
        $config = config('services.msg91');

        if (!$config['auth_key'] || !$config['template_id']) {
            return [
                'ok' => false,
                'message' => 'SMS gateway is not configured.',
                'debug' => [
                    'phone' => $phone,
                    'auth_key_present' => !empty($config['auth_key']),
                    'template_id_present' => !empty($config['template_id']),
                ],
            ];
        }

        $sendUrl = $config['send_url'] ?? 'https://control.msg91.com/api/v5/otp';
        $mobile = $this->mobileWithCountryCode($phone, $config);
        $otpLength = 6;
        $params = [
            'template_id' => $config['template_id'],
            'mobile' => $mobile,
            'otp_length' => $otpLength,
            'authkey' => $config['auth_key'],
        ];

        $query = http_build_query($params);

        $requestUrl = $sendUrl . '?' . $query;
        $maskedAuthKey = !empty($config['auth_key']) ? substr($config['auth_key'], 0, 4) . '...' : null;
        $debug = [
            'phone' => $phone,
            'send_url' => $sendUrl,
            'request_url' => preg_replace('/authkey=[^&]+/i', 'authkey=***REDACTED***', $requestUrl),
            'query_params' => [
                'template_id' => $config['template_id'],
                'mobile' => $phone,
                'otp_length' => $otpLength,
                'authkey' => $maskedAuthKey,
            ],
            'headers' => [
                'Content-Type' => 'application/json',
                'authkey' => $maskedAuthKey,
            ],
            'payload' => [],
            'verify_ssl' => (bool) ($config['verify_ssl'] ?? false),
        ];

        Log::info('MSG91 send OTP request', $debug);

        $response = Http::withOptions([
            'verify' => (bool) ($config['verify_ssl'] ?? false),
        ])->withHeaders([
            'Content-Type' => 'application/json',
            'authkey' => $config['auth_key'],
        ])->post($requestUrl, []);

        $responseJson = $response->json();
        $debug['status_code'] = $response->status();
        $debug['response_body'] = $response->body();
        $debug['response_json'] = $responseJson;
        $debug['response_message'] = is_array($responseJson) && isset($responseJson['message']) ? $responseJson['message'] : null;
        $debug['successful'] = $response->successful();

        Log::info('MSG91 send OTP response', $debug);

        if (!$response->successful()) {
            return [
                'ok' => false,
                'message' => 'Failed to send OTP.',
                'debug' => $debug,
            ];
        }

        return [
            'ok' => true,
            'message' => 'OTP sent successfully.',
            'debug' => $debug,
        ];
    }

    private function verifyOtpWithMsg91(string $phone, string $otp): array
    {
        $config = config('services.msg91');

        if (!$config['auth_key']) {
            return [
                'ok' => false,
                'message' => 'SMS gateway is not configured.',
                'debug' => [
                    'phone' => $phone,
                    'otp' => $otp,
                    'auth_key_present' => !empty($config['auth_key']),
                ],
            ];
        }

        $verifyUrl = $config['verify_url'] ?? 'https://control.msg91.com/api/v5/otp/verify';
        $mobile = $this->mobileWithCountryCode($phone, $config);
        $maskedAuthKey = !empty($config['auth_key']) ? substr($config['auth_key'], 0, 4) . '...' : null;
        $debug = [
            'phone' => $phone,
            'otp' => $otp,
            'verify_url' => $verifyUrl,
            'query_params' => [
                'mobile' => $mobile,
                'otp' => $otp,
            ],
            'headers' => [
                'authkey' => $maskedAuthKey,
            ],
            'verify_ssl' => (bool) ($config['verify_ssl'] ?? false),
        ];

        Log::info('MSG91 verify OTP request', $debug);

        $response = Http::withOptions([
            'verify' => (bool) ($config['verify_ssl'] ?? false),
        ])->withHeaders([
            'authkey' => $config['auth_key'],
        ])->get($verifyUrl, [
            'mobile' => $mobile,
            'otp' => $otp,
        ]);

        $responseJson = $response->json();
        $debug['status_code'] = $response->status();
        $debug['response_body'] = $response->body();
        $debug['response_json'] = $responseJson;
        $debug['response_message'] = is_array($responseJson) && isset($responseJson['message']) ? $responseJson['message'] : null;
        $debug['successful'] = $response->successful();

        Log::info('MSG91 verify OTP response', $debug);

        if (!$response->successful()) {
            return [
                'ok' => false,
                'message' => 'The OTP is invalid or expired.',
                'debug' => $debug,
            ];
        }

        $body = $response->json();

        if (is_array($body) && isset($body['type']) && in_array(strtolower((string) $body['type']), ['error', 'failed', 'failure'], true)) {
            return [
                'ok' => false,
                'message' => $body['message'] ?? 'The OTP is invalid or expired.',
                'debug' => $debug,
            ];
        }

        return [
            'ok' => true,
            'message' => 'OTP verified successfully.',
            'debug' => $debug,
        ];
    }

    private function resendOtpWithMsg91(string $phone): array
    {
        $config = config('services.msg91');

        if (!$config['auth_key']) {
            return [
                'ok' => false,
                'message' => 'SMS gateway is not configured.',
                'debug' => [
                    'phone' => $phone,
                    'auth_key_present' => !empty($config['auth_key']),
                ],
            ];
        }

        $resendUrl = $config['resend_url'] ?? 'https://control.msg91.com/api/v5/otp/retry';
        $mobile = $this->mobileWithCountryCode($phone, $config);
        $query = http_build_query([
            'authkey' => $config['auth_key'],
            'retrytype' => $config['retry_type'] ?? 'text',
            'mobile' => $mobile,
        ]);

        $requestUrl = $resendUrl . '?' . $query;
        $maskedAuthKey = !empty($config['auth_key']) ? substr($config['auth_key'], 0, 4) . '...' : null;
        $debug = [
            'phone' => $phone,
            'resend_url' => $resendUrl,
            'request_url' => preg_replace('/authkey=[^&]+/i', 'authkey=***REDACTED***', $requestUrl),
            'query_params' => [
                'authkey' => $maskedAuthKey,
                'retrytype' => $config['retry_type'] ?? 'text',
                'mobile' => $phone,
            ],
            'verify_ssl' => (bool) ($config['verify_ssl'] ?? false),
        ];

        Log::info('MSG91 resend OTP request', $debug);

        $response = Http::withOptions([
            'verify' => (bool) ($config['verify_ssl'] ?? false),
        ])->get($requestUrl);

        $responseJson = $response->json();
        $debug['status_code'] = $response->status();
        $debug['response_body'] = $response->body();
        $debug['response_json'] = $responseJson;
        $debug['response_message'] = is_array($responseJson) && isset($responseJson['message']) ? $responseJson['message'] : null;
        $debug['successful'] = $response->successful();

        Log::info('MSG91 resend OTP response', $debug);

        if (!$response->successful()) {
            return [
                'ok' => false,
                'message' => 'Failed to resend OTP.',
                'debug' => $debug,
            ];
        }

        return [
            'ok' => true,
            'message' => 'OTP resent successfully.',
            'debug' => $debug,
        ];
    }

    // MSG91 requires the mobile number prefixed with the country code (e.g. 91XXXXXXXXXX)
    private function mobileWithCountryCode(string $phone, array $config): string
    {
        $country = $config['country'] ?? '91';

        return str_starts_with($phone, $country) ? $phone : $country . $phone;
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $request->user()->id,
            'gender' => 'required|string|in:male,female,other',
        ]);

        $user = $request->user();
        $user->update($request->only(['first_name', 'last_name', 'email', 'gender']));

        return response()->json([
            'message' => 'Profile updated successfully',
            'customer' => $user,
        ]);
    }
} 