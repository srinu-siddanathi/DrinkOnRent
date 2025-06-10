<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Subscription::with(['customer', 'plan'])
            ->latest()
            ->paginate(10);
            
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Subscription $order)
    {
        $order->load(['customer', 'plan']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Subscription $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,active,expired,cancelled',
            'payment_status' => 'required|in:pending,completed,failed',
        ]);

        $order->update($validated);

        if ($validated['status'] === 'active' && $order->start_date === null) {
            $order->update([
                'start_date' => now(),
                'end_date' => now()->addDays($order->plan->duration_days),
            ]);
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully');
    }
} 