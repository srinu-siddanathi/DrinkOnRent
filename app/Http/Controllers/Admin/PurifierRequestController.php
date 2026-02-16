<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurifierRequest;
use Illuminate\Http\Request;

class PurifierRequestController extends Controller
{
    public function index()
    {
        $purifierRequests = PurifierRequest::with('customer')->latest()->get();
        return view('admin.purifier-requests.index', compact('purifierRequests'));
    }

    public function show(PurifierRequest $purifierRequest)
    {
        return view('admin.purifier-requests.show', compact('purifierRequest'));
    }

    public function update(Request $request, PurifierRequest $purifierRequest)
    {
        $request->validate([
            'status' => 'required|string|in:approved,rejected,in_progress,completed',
        ]);

        $purifierRequest->update(['status' => $request->status]);

        // TODO: Add notification to customer

        return redirect()->route('admin.purifier-requests.show', $purifierRequest)->with('success', 'Request status updated successfully.');
    }
}