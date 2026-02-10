<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

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

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully');
    }
} 