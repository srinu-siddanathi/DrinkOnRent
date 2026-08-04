<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
            ];
        }

        $query = http_build_query([
            'template_id' => $config['template_id'],
            'mobile' => $phone,
            'authkey' => $config['auth_key'],
        ]);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'authkey' => $config['auth_key'],
        ])->post('https://control.msg91.com/api/v5/otp?' . $query, []);

        if (!$response->successful()) {
            return [
                'ok' => false,
                'message' => 'Failed to send OTP.',
            ];
        }

        return [
            'ok' => true,
            'message' => 'OTP sent successfully.',
        ];
    }

    private function verifyOtpWithMsg91(string $phone, string $otp): array
    {
        $config = config('services.msg91');

        if (!$config['auth_key']) {
            return [
                'ok' => false,
                'message' => 'SMS gateway is not configured.',
            ];
        }

        $response = Http::withHeaders([
            'authkey' => $config['auth_key'],
        ])->get('https://control.msg91.com/api/v5/otp/verify', [
            'mobile' => $phone,
            'otp' => $otp,
        ]);

        if (!$response->successful()) {
            return [
                'ok' => false,
                'message' => 'The OTP is invalid or expired.',
            ];
        }

        $body = $response->json();

        if (is_array($body) && isset($body['type']) && in_array(strtolower((string) $body['type']), ['error', 'failed', 'failure'], true)) {
            return [
                'ok' => false,
                'message' => $body['message'] ?? 'The OTP is invalid or expired.',
            ];
        }

        return [
            'ok' => true,
            'message' => 'OTP verified successfully.',
        ];
    }

    private function resendOtpWithMsg91(string $phone): array
    {
        $config = config('services.msg91');

        if (!$config['auth_key']) {
            return [
                'ok' => false,
                'message' => 'SMS gateway is not configured.',
            ];
        }

        $query = http_build_query([
            'authkey' => $config['auth_key'],
            'retrytype' => $config['retry_type'] ?? 'text',
            'mobile' => $phone,
        ]);

        $response = Http::get('https://control.msg91.com/api/v5/otp/retry?' . $query);

        if (!$response->successful()) {
            return [
                'ok' => false,
                'message' => 'Failed to resend OTP.',
            ];
        }

        return [
            'ok' => true,
            'message' => 'OTP resent successfully.',
        ];
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