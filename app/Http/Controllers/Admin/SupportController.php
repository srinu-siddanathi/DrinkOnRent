<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportRequest;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportRequest::with('customer')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('customer', function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%");
            });
        }

        $requests = $query->paginate(10);
            
        return view('admin.support.index', compact('requests'));
    }

    public function show(SupportRequest $supportRequest)
    {
        $supportRequest->load(['customer', 'services' => function ($query) {
            $query->latest('service_date');
        }]);
        return view('admin.support.show', compact('supportRequest'));
    }

    public function update(Request $request, SupportRequest $supportRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'admin_notes' => 'nullable|string',
        ]);

        $supportRequest->update($validated);

        return redirect()->route('admin.support-requests.show', $supportRequest)
            ->with('success', 'Support request updated successfully');
    }

    public function search(Request $request)
    {
        $query = SupportRequest::with('customer')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $query->whereHas('customer', function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%");
            });
        }

        $requests = $query->limit(100)->get();

        return response()->json([
            'requests' => $requests,
        ]);
    }
} 