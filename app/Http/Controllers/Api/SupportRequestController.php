<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportRequest;
use Illuminate\Http\Request;

class SupportRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $supportRequest = auth()->user()->supportRequests()->create([
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'open',
        ]);

        return response()->json([
            'message' => 'Support request created successfully',
            'support_request' => $supportRequest,
        ], 201);
    }

    public function index()
    {
        $requests = auth()->user()->supportRequests()
            ->latest()
            ->get();

        return response()->json([
            'support_requests' => $requests,
        ]);
    }

    public function show(SupportRequest $supportRequest)
    {
        // Ensure the user can only view their own support requests
        if ($supportRequest->customer_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized access',
            ], 403);
        }

        return response()->json([
            'support_request' => $supportRequest,
        ]);
    }
} 