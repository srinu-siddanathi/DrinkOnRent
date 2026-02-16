<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PurifierRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurifierRequestController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'purifier_type' => 'required|string|in:ro,alkaline',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'location_address' => 'required|string|max:255',
            'source_of_drinking_water' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'type_of_residence' => 'nullable|string|max:255',
            'share_with' => 'nullable|string|max:255',
            'current_profession' => 'nullable|string|max:255',
        ]);

        $customer = Auth::user();

        $existingRequest = $customer->purifierRequests()
            ->whereNotIn('status', ['completed', 'rejected'])
            ->exists();

        if ($existingRequest) {
            return response()->json([
                'message' => 'You already have an open purifier request.',
            ], 409);
        }

        $purifierRequest = $customer->purifierRequests()->create($validatedData);

        return response()->json([
            'message' => 'Purifier request submitted successfully.',
            'data' => $purifierRequest,
        ], 201);
    }
}