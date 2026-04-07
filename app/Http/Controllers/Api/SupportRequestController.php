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

        $supportRequest->load([
            'services' => function ($query) {
                $query->latest('service_date');
            },
        ]);

        $services = $supportRequest->services->map(function ($service) {
            return [
                'id' => $service->id,
                'service_date' => $service->service_date?->format('d-m-Y') ?? '-',
                'next_service_reminder' => "{$service->next_service_reminder} Months",
                'expiry_date' => $service->expiry_date?->format('d-m-Y') ?? '-',
                'spare_parts' => $service->spare_parts ?? [],
                'images' => collect($service->images ?? [])->map(function ($image) {
                    return asset('uploads/service-images/' . $image);
                })->values(),
            ];
        })->values();

        return response()->json([
            'support_request' => [
                'id' => $supportRequest->id,
                'customer_id' => $supportRequest->customer_id,
                'subject' => $supportRequest->subject,
                'message' => $supportRequest->message,
                'status' => $supportRequest->status,
                'admin_notes' => $supportRequest->admin_notes,
                'created_at' => optional($supportRequest->created_at)->toISOString(),
                'updated_at' => optional($supportRequest->updated_at)->toISOString(),
                'services' => $services,
            ],
        ]);
    }
} 