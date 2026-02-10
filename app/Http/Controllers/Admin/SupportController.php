<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportRequest;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        $requests = SupportRequest::with('customer')
            ->latest()
            ->paginate(10);
            
        return view('admin.support.index', compact('requests'));
    }

    public function show(SupportRequest $supportRequest)
    {
        $supportRequest->load('customer');
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
} 