<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\Messaging\SmsService;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    // Test endpoint to manually trigger the MSG91 rental reminder SMS for a given subscription or customer phone
    public function test(Request $request, SmsService $smsService)
    {
        $request->validate([
            'subscription_id' => 'required_without:phone|integer|exists:subscriptions,id',
            'phone' => 'required_without:subscription_id|string|size:10',
        ]);

        $subscription = $request->filled('subscription_id')
            ? Subscription::with(['customer', 'plan'])->find($request->subscription_id)
            : Subscription::with(['customer', 'plan'])
                ->whereHas('customer', fn ($q) => $q->where('phone', $request->phone))
                ->latest('end_date')
                ->first();

        if (!$subscription) {
            return response()->json([
                'message' => 'No matching subscription found.',
            ], 404);
        }

        $customer = $subscription->customer;

        if (!$customer || !$customer->phone) {
            return response()->json([
                'message' => 'Customer has no phone number on file.',
            ], 422);
        }

        $templateId = config('services.msg91.reminder_template_id');

        if (!$templateId) {
            return response()->json([
                'message' => 'MSG91 reminder template ID is not configured.',
            ], 502);
        }

        $result = $smsService->sendFlowSms($customer->phone, $templateId, [
            'name' => trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? '')) ?: $customer->phone,
            'number' => number_format((float) optional($subscription->plan)->price, 2, '.', ''),
            'date' => optional($subscription->end_date)->format('d-m-Y'),
        ]);

        return response()->json([
            'ok' => $result['ok'],
            'message' => $result['message'],
            'subscription_id' => $subscription->id,
            'phone' => $customer->phone,
        ], $result['ok'] ? 200 : 502);
    }
}
