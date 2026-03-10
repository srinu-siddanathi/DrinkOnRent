<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purifier;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;

class PurifierController extends Controller
{
    public function index()
    {
        $purifiers = Purifier::with([
            'customer',
            'subscriptions.plan',
            'customer.subscriptions.plan'
        ])->paginate(10);
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
            'mac_address' => 'nullable|string|unique:purifiers,mac_address',
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
        $purifier->load(['customer', 'subscriptions.plan', 'customer.subscriptions.plan']);

        $subscriptionIds = Subscription::where('purifier_id', $purifier->id)->pluck('id');

        if ($subscriptionIds->isEmpty() && $purifier->customer_id) {
            $subscriptionIds = Subscription::where('customer_id', $purifier->customer_id)->pluck('id');
        }

        $payments = Payment::with(['subscription.plan'])
            ->whereIn('subscription_id', $subscriptionIds)
            ->latest()
            ->get();

        $fallbackPayments = collect();

        if ($payments->isEmpty()) {
            $fallbackPayments = Subscription::with('plan')
                ->whereIn('id', $subscriptionIds)
                ->whereNotNull('payment_status')
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($subscription) {
                    return (object) [
                        'created_at' => $subscription->created_at,
                        'plan_name' => $subscription->plan->name ?? '-',
                        'amount' => $subscription->plan->price ?? 0,
                        'status' => $subscription->payment_status,
                        'razorpay_order_id' => '-',
                        'razorpay_payment_id' => '-',
                    ];
                });
        }

        return view('admin.purifiers.show', compact('purifier', 'payments', 'fallbackPayments'));
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
            'mac_address' => 'nullable|string|unique:purifiers,mac_address,' . $purifier->id,
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