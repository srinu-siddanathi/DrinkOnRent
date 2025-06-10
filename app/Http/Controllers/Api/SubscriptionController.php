<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $customer = auth()->user();
        $plan = Plan::findOrFail($request->plan_id);

        $subscription = $customer->subscriptions()->create([
            'plan_id' => $plan->id,
            'litres_remaining' => $plan->litres_per_month,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Subscription created successfully',
            'subscription' => $subscription->load('plan'),
        ]);
    }

    public function activate(Request $request, Subscription $subscription)
    {
        // Verify payment status (integrate with your payment gateway)
        if ($subscription->payment_status !== 'completed') {
            return response()->json([
                'message' => 'Payment not completed'
            ], 400);
        }

        $subscription->update([
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addDays($subscription->plan->duration_days),
        ]);

        return response()->json([
            'message' => 'Subscription activated successfully',
            'subscription' => $subscription->load('plan'),
        ]);
    }

    public function active()
    {
        $subscription = auth()->user()->activeSubscription()->with('plan')->first();
        
        if (!$subscription) {
            return response()->json([
                'message' => 'No active subscription found'
            ], 404);
        }

        return response()->json([
            'subscription' => $subscription,
            'days_remaining' => now()->diffInDays($subscription->end_date, false),
            'litres_remaining' => $subscription->litres_remaining,
        ]);
    }

    public function updateConsumption(Request $request, $subscriptionId)
    {
        // Validate the request
        $request->validate([
            'litres_consumed' => 'required|integer|min:0',
            'litres_remaining' => 'required|integer|min:0',
        ]);

        // Find the subscription
        $subscription = Subscription::findOrFail($subscriptionId);

        // Update the consumption details
        $subscription->litres_consumed = $request->litres_consumed;
        $subscription->litres_remaining = $request->litres_remaining;
        $subscription->save();

        return response()->json([
            'message' => 'Consumption details updated successfully',
            'subscription' => $subscription,
        ]);
    }
} 