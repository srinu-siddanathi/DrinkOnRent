<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with(['latestService', 'purifiers']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('area')) {
             $query->where('area', $request->area);
        }

        $customers = $query->latest()->paginate(10);
        $areas = Customer::distinct()->pluck('area')->filter();

        return view('admin.services.index', compact('customers', 'areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_date' => 'required|date',
            'next_service_reminder' => 'required|integer|in:3,6,12',
            'spare_parts' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $serviceData = $validated;

        // Handle Images
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/service-images'), $imageName);
                $imagePaths[] = $imageName;
            }
            $serviceData['images'] = $imagePaths;
        }

        // Calculate Expiry Date
        $serviceDate = Carbon::parse($validated['service_date']);
        $serviceData['expiry_date'] = $serviceDate->copy()->addMonths((int)$validated['next_service_reminder']);

        Service::create($serviceData);

        return redirect()->back()->with('success', 'Service added successfully');
    }

    public function history(Customer $customer)
    {
        $services = $customer->services()->latest('service_date')->get();
        // We will return JSON with HTML, assuming the partial exists or we construct it here.
        // For simplicity, let's return the data and let JS build it, OR return a partial.
        // Let's return the partial.
        return response()->json([
            'html' => view('admin.services.partials.history', compact('services'))->render()
        ]);
    }
}
