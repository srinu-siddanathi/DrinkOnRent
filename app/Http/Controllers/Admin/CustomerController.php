<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Purifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with([
            'subscriptions' => function($query) {
                $query->where('status', 'active')
                      ->where('end_date', '>', now())
                      ->orderBy('end_date', 'desc');
            },
            'subscriptions.plan',
            'subscriptions.purifier',
            'purifiers'
        ])->latest()->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        $customer->load(['subscriptions.plan', 'purifiers']);
        return view('admin.customers.show', compact('customer'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function edit(Customer $customer)
    {
        $customer->load('purifiers');
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'phone' => ['required', 'regex:/^[0-9]{10}$/', Rule::unique('customers', 'phone')->ignore($customer->id)],
            'email' => ['nullable', 'email', Rule::unique('customers', 'email')->ignore($customer->id)],
            'address' => 'required|string|max:500',
            'area' => 'required|string|max:255',
            'id_proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'purifier_code' => 'nullable|string|max:255',
            'purifier_type' => 'nullable|string|in:ro,alkaline',
            'installation_date' => 'nullable|date',
        ]);

        try {
            // Handle ID Proof Upload
            if ($request->hasFile('id_proof')) {
                // Delete old file if exists
                if ($customer->id_proof && Storage::disk('local')->exists('id-proofs/' . $customer->id_proof)) {
                    Storage::disk('local')->delete('id-proofs/' . $customer->id_proof);
                }

                $file = $request->file('id_proof');
                $filename = time() . '_' . $request->phone . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->putFileAs('id-proofs', $file, $filename);
                $validated['id_proof'] = $filename;
            }

            // Update Customer
            $customer->update($validated);

            // Update or create Purifier if data provided
            if ($customer->purifiers->count() > 0) {
                $purifier = $customer->purifiers->first();
                if ($validated['purifier_code'] || $validated['purifier_type'] || $validated['installation_date']) {
                    $purifierData = [];
                    if ($validated['purifier_code']) $purifierData['purifier_code'] = $validated['purifier_code'];
                    if ($validated['purifier_type']) $purifierData['type'] = $validated['purifier_type'];
                    if ($validated['installation_date']) $purifierData['installation_date'] = $validated['installation_date'];
                    $purifier->update($purifierData);
                }
            }

            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error during update: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'phone' => ['required', 'regex:/^[0-9]{10}$/', Rule::unique('customers', 'phone')],
            'email' => ['nullable', 'email', Rule::unique('customers', 'email')],
            'address' => 'required|string|max:500',
            'area' => 'required|string|max:255',
            'id_proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'purifier_code' => 'required|string|max:255',
            'purifier_type' => 'required|string|in:ro,alkaline',
            'installation_date' => 'required|date',
        ]);

        try {
            // Handle ID Proof Upload
            if ($request->hasFile('id_proof')) {
                $file = $request->file('id_proof');
                $filename = time() . '_' . $request->phone . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->putFileAs('id-proofs', $file, $filename);
                $validated['id_proof'] = $filename;
            }

            // Create Customer
            $customer = Customer::create($validated);

            // Create Purifier
            Purifier::create([
                'customer_id' => $customer->id,
                'type' => $validated['purifier_type'],
                'installation_date' => $validated['installation_date'],
            ]);

            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer and Purifier registered successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error during registration: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function downloadIdProof(Customer $customer)
    {
        if (!$customer->id_proof) {
            return redirect()->back()->with('error', 'ID proof not found');
        }

        $path = 'id-proofs/' . $customer->id_proof;

        if (!Storage::disk('local')->exists($path)) {
            return redirect()->back()->with('error', 'File not found');
        }

        return Storage::download($path, $customer->name . '_id_proof.' . pathinfo($customer->id_proof, PATHINFO_EXTENSION));
    }

    public function destroy(Customer $customer)
    {
        // Delete ID Proof if exists
        if ($customer->id_proof && Storage::disk('local')->exists('id-proofs/' . $customer->id_proof)) {
            Storage::disk('local')->delete('id-proofs/' . $customer->id_proof);
        }

        $customer->delete();
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully');
    }
} 