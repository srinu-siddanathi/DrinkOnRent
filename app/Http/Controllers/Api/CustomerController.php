<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . auth()->id(),
            'address' => 'nullable|string',
        ]);

        $customer = auth()->user();
        $customer->update($request->only(['name', 'email', 'address']));

        return response()->json([
            'message' => 'Profile updated successfully',
            'customer' => $customer,
        ]);
    }

    public function dashboard()
    {
        $customer = auth()->user();
        $activeSubscription = $customer->activeSubscription()->with('plan')->first();
        $purifiers = $customer->purifiers()->get();

        return response()->json([
            'customer' => $customer,
            'subscription' => $activeSubscription,
            'purifiers' => $purifiers,
            'days_remaining' => $activeSubscription ? now()->diffInDays($activeSubscription->end_date, false) : 0,
            'litres_remaining' => $activeSubscription ? $activeSubscription->litres_remaining : 0,
        ]);
    }
} 