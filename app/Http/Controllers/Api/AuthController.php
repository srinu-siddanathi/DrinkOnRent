<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|size:10',
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store OTP in cache for 5 minutes
        cache()->put('otp_' . $request->phone, $otp, now()->addMinutes(5));

        // TODO: Integrate with SMS gateway to send OTP
        // For now, return OTP in response (only for development)
        return response()->json([
            'message' => 'OTP sent successfully',
            'otp' => $otp, // Remove this in production
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|size:10',
            'otp' => 'required|string|size:6',
        ]);

        $cachedOtp = cache()->get('otp_' . $request->phone);

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            throw ValidationException::withMessages([
                'otp' => ['The OTP is invalid or expired.'],
            ]);
        }

        $customer = Customer::firstOrCreate(
            ['phone' => $request->phone],
            ['is_phone_verified' => true]
        );

        cache()->forget('otp_' . $request->phone);

        return response()->json([
            'message' => 'OTP verified successfully',
            'token' => $customer->createToken('auth-token')->plainTextToken,
            'customer' => $customer,
        ]);
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