<?php

namespace App\Http\Controllers;

use App\Models\Purifier;
use App\Models\Customer;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PurifierController extends Controller
{
    public function create(Request $request)
    {
        // Get customer_id from the authenticated user
        $customer_id = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'model' => 'required|string',
            'type' => 'required|string',
            'installation_date' => 'nullable|date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location_address' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['customer_id'] = $customer_id; // Set customer_id from token

        if (isset($data['installation_date'])) {
            $data['installation_date'] = Carbon::parse($data['installation_date'])->setTimezone('Asia/Kolkata');
        }

        $purifier = Purifier::create($data);

        return response()->json([
            'message' => 'Purifier created successfully',
            'purifier' => $purifier
        ], 201);
    }

    public function setPlan(Request $request, $purifierId)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $purifier = Purifier::where('id', $purifierId)
            ->where('customer_id', Auth::user()->id)
            ->firstOrFail();

        $subscription = $purifier->customer->subscriptions()->create([
            'plan_id' => $request->plan_id,
            'purifier_id' => $purifierId,
            'start_date' => Carbon::parse($request->start_date)->setTimezone('Asia/Kolkata'),
            'end_date' => Carbon::parse($request->end_date)->setTimezone('Asia/Kolkata'),
            'status' => 'active'
        ]);

        return response()->json([
            'message' => 'Plan set successfully',
            'subscription' => $subscription
        ]);
    }

    public function setRtcError(Request $request, $purifierId)
    {
        $validator = Validator::make($request->all(), [
            'has_rtc_error' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $purifier = Purifier::where('id', $purifierId)
            ->where('customer_id', Auth::user()->id)
            ->firstOrFail();
        
        $purifier->update([
            'has_rtc_error' => $request->has_rtc_error,
            'rtc_error_updated_at' => Carbon::now('Asia/Kolkata')
        ]);

        return response()->json([
            'message' => 'RTC error status updated successfully',
            'purifier' => $purifier
        ]);
    }

    public function getCurrentDateTime()
    {
        return response()->json([
            'current_datetime' => Carbon::now('Asia/Kolkata')->toIso8601String(),
            'timezone' => 'Asia/Kolkata (UTC+5:30)'
        ]);
    }

    public function clearRtcError(Request $request, $purifierId)
    {
        $purifier = Purifier::where('id', $purifierId)
            ->where('customer_id', Auth::user()->id)
            ->firstOrFail();
        
        $purifier->update([
            'has_rtc_error' => false,
            'rtc_error_updated_at' => Carbon::now('Asia/Kolkata')
        ]);

        return response()->json([
            'message' => 'RTC error cleared successfully',
            'purifier' => $purifier
        ]);
    }
} 