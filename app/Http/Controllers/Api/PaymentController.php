<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private $razorpayKey;
    private $razorpaySecret;

    public function __construct()
    {
        $this->razorpayKey = config('services.razorpay.key');
        $this->razorpaySecret = config('services.razorpay.secret');
    }

    public function createOrder(Request $request, $subscriptionId)
    {
        $subscription = Subscription::with(['plan', 'customer'])->findOrFail($subscriptionId);

        if ($subscription->customer_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized access to subscription'], 403);
        }

        if (!$subscription->plan) {
            return response()->json(['message' => 'Subscription plan not found'], 404);
        }

        $price = $subscription->plan->price;
        $amountInPaise = $price * 100;

        $api = app(Api::class);

        $orderData = [
            'receipt'         => 'rcpt_' . $subscription->id . '_' . time(),
            'amount'          => $amountInPaise,
            'currency'        => 'INR',
            'notes'           => [
                'subscription_id' => $subscription->id,
                'customer_id' => $subscription->customer_id,
            ]
        ];

        try {
            $razorpayOrder = $api->order->create($orderData);
        } catch (\Exception $e) {
            Log::error('Razorpay Order Creation Failed: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to create payment order', 'error' => $e->getMessage()], 500);
        }

        Payment::create([
            'subscription_id' => $subscription->id,
            'razorpay_order_id' => $razorpayOrder->id,
            'amount' => $price,
            'currency' => 'INR',
            'status' => 'pending',
        ]);

        return response()->json([
            'order_id' => $razorpayOrder->id,
            'amount' => $amountInPaise,
            'currency' => 'INR',
            'key' => $this->razorpayKey,
            'name' => config('app.name'),
            'description' => 'Payment for ' . $subscription->plan->name,
            'prefill' => [
                'name' => $subscription->customer->name ?? '',
                'email' => $subscription->customer->email ?? '',
                'contact' => $subscription->customer->phone ?? '',
            ],
            'theme' => [
                'color' => '#3399cc'
            ]
        ]);
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $api = app(Api::class);

        $attributes = [
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature
        ];

        try {
            $api->utility->verifyPaymentSignature($attributes);
        } catch (\Exception $e) {
             Log::error('Razorpay Signature Verification Failed: ' . $e->getMessage());

             $payment = Payment::where('razorpay_order_id', $request->razorpay_order_id)->first();
             if ($payment) {
                 $payment->update(['status' => 'failed']);
                 $payment->subscription->update(['payment_status' => 'failed']);
             }

             return response()->json(['message' => 'Payment verification failed', 'error' => $e->getMessage()], 400);
        }

        $payment = Payment::where('razorpay_order_id', $request->razorpay_order_id)->firstOrFail();

        if ($payment->status === 'completed') {
             return response()->json(['message' => 'Payment already verified']);
        }

        $payment->update([
            'status' => 'completed',
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature,
        ]);

        $subscription = $payment->subscription;

        $subscription->update([
            'payment_status' => 'completed',
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addDays($subscription->plan->duration_in_days),
        ]);

        return response()->json([
            'message' => 'Payment successful and subscription activated',
            'subscription' => $subscription
        ]);
    }
}
