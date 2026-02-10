<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purifier;
use App\Models\Customer;
use Illuminate\Http\Request;

class PurifierController extends Controller
{
    public function index()
    {
        $purifiers = Purifier::with('customer')->paginate(10);
        return view('admin.purifiers.index', compact('purifiers'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('admin.purifiers.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'serial_number' => 'required|unique:purifiers',
            'model' => 'required',
            'type' => 'required|in:alkaline,ro',
            'status' => 'required|in:available,assigned,maintenance,retired',
            'customer_id' => 'nullable|exists:customers,id',
            'installation_date' => 'nullable|date',
            'last_service_date' => 'nullable|date',
            'next_service_date' => 'nullable|date',
        ]);

        Purifier::create($validated);

        return redirect()->route('admin.purifiers.index')
            ->with('success', 'Purifier added successfully');
    }

    public function show(Purifier $purifier)
    {
        return view('admin.purifiers.show', compact('purifier'));
    }

    public function edit(Purifier $purifier)
    {
        $customers = Customer::all();
        return view('admin.purifiers.edit', compact('purifier', 'customers'));
    }

    public function update(Request $request, Purifier $purifier)
    {
        $validated = $request->validate([
            'serial_number' => 'required|unique:purifiers,serial_number,' . $purifier->id,
            'model' => 'required',
            'type' => 'required|in:alkaline,ro',
            'status' => 'required|in:available,assigned,maintenance,retired',
            'customer_id' => 'nullable|exists:customers,id',
            'installation_date' => 'nullable|date',
            'last_service_date' => 'nullable|date',
            'next_service_date' => 'nullable|date',
        ]);

        $purifier->update($validated);

        return redirect()->route('admin.purifiers.index')
            ->with('success', 'Purifier updated successfully');
    }
} 