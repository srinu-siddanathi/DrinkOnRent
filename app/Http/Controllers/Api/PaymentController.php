<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function processPayment(Request $request, $subscriptionId)
    {
        // Validate the request
        $request->validate([
            'payment_method' => 'required|string', // Example: payment method token
        ]);

        // Find the subscription
        $subscription = Subscription::findOrFail($subscriptionId);

        // Here you would integrate with your payment gateway
        // For example, using Stripe:
        // $payment = Stripe::charges()->create([...]);

        // Simulate payment processing
        $paymentSuccessful = true; // Replace with actual payment logic

        if ($paymentSuccessful) {
            // Update the subscription payment status
            $subscription->update([
                'payment_status' => 'completed',
                'status' => 'active', // Activate the subscription
                'start_date' => now(),
                'end_date' => now()->addDays($subscription->plan->duration_days),
            ]);

            return response()->json([
                'message' => 'Payment successful and subscription activated',
                'subscription' => $subscription,
            ]);
        } else {
            return response()->json([
                'message' => 'Payment failed',
            ], 400);
        }
    }
} 