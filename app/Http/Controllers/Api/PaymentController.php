<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Purifier;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
            'purifier_id' => 'nullable|integer|exists:purifiers,id',
        ]);

        $payment = Payment::with(['subscription.plan'])
            ->where('razorpay_order_id', $request->razorpay_order_id)
            ->firstOrFail();

        $subscription = $payment->subscription;
        if (!$subscription || $subscription->customer_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized access to payment'], 403);
        }

        if (!$subscription->plan) {
            return response()->json(['message' => 'Subscription plan not found'], 404);
        }

        $purifier = null;
        if ($request->filled('purifier_id')) {
            $purifier = Purifier::where('id', $request->purifier_id)
                ->where('customer_id', $request->user()->id)
                ->first();

            if (!$purifier) {
                return response()->json(['message' => 'Invalid purifier for this customer'], 422);
            }
        }

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

               if ($payment->status !== 'completed') {
                  $payment->update(['status' => 'failed']);
                  $subscription->update(['payment_status' => 'failed']);
               }

             return response()->json(['message' => 'Payment verification failed', 'error' => $e->getMessage()], 400);
        }

        DB::transaction(function () use ($payment, $subscription, $request, $purifier) {
            $lockedPayment = Payment::whereKey($payment->id)->lockForUpdate()->first();
            $lockedSubscription = Subscription::with('plan')
                ->whereKey($subscription->id)
                ->lockForUpdate()
                ->first();

            if (!$lockedPayment || !$lockedSubscription) {
                abort(404);
            }

            if ($lockedPayment->status !== 'completed') {
                $lockedPayment->update([
                    'status' => 'completed',
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature,
                ]);

                $lockedSubscription->update([
                    'payment_status' => 'completed',
                    'status' => 'active',
                    'start_date' => now(),
                    'end_date' => now()->addDays($lockedSubscription->plan->duration_in_days),
                ]);
            }

            if ($purifier && is_null($lockedSubscription->purifier_id)) {
                $lockedSubscription->update([
                    'purifier_id' => $purifier->id,
                ]);
            }
        });

        $subscription = Subscription::with('plan')->findOrFail($subscription->id);

        $message = $payment->status === 'completed'
            ? 'Payment already verified; subscription is active'
            : 'Payment successful and subscription activated';

        return response()->json([
            'message' => $message,
            'subscription' => $subscription
        ]);
    }

    public function history(Request $request)
    {
        $payments = Payment::with(['subscription.plan'])
            ->whereHas('subscription', function ($query) use ($request) {
                $query->where('customer_id', $request->user()->id);
            })
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'message' => $payments->isEmpty()
                ? 'Payment history is empty'
                : 'Payment history fetched successfully',
            'payments' => $payments->map(function (Payment $payment) {
                $subscription = $payment->subscription;
                $plan = $subscription?->plan;

                return [
                    'id' => $payment->id,
                    'razorpay_order_id' => $payment->razorpay_order_id,
                    'razorpay_payment_id' => $payment->razorpay_payment_id,
                    'amount' => (float) $payment->amount,
                    'currency' => $payment->currency,
                    'status' => $payment->status,
                    'created_at' => optional($payment->created_at)->toISOString(),
                    'subscription' => $subscription ? [
                        'id' => $subscription->id,
                        'status' => $subscription->status,
                        'start_date' => optional($subscription->start_date)->toISOString(),
                        'end_date' => optional($subscription->end_date)->toISOString(),
                    ] : null,
                    'plan' => $plan ? [
                        'id' => $plan->id,
                        'name' => $plan->name,
                        'price' => (float) $plan->price,
                        'duration_in_days' => $plan->duration_in_days,
                    ] : null,
                ];
            })->values(),
        ]);
    }
}
