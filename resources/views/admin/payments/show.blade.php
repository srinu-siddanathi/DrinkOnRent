@extends('admin.layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="space-y-6">
    <!-- Payment Summary Card -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Payment Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                    <dd class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $payment->payment_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($payment->payment_status) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Amount</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900">₹{{ number_format($payment->plan->price, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Payment Date</dt>
                    <dd class="mt-1 text-gray-900">{{ $payment->created_at->format('M d, Y h:i A') }}</dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Details -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Customer Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                    <dd class="mt-1 text-gray-900">{{ $payment->customer->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-gray-900">{{ $payment->customer->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                    <dd class="mt-1 text-gray-900">{{ $payment->customer->phone }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                    <dd class="mt-1 text-gray-900">{{ $payment->customer->address }}</dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Plan Details -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Plan Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Plan Name</dt>
                    <dd class="mt-1 text-gray-900">{{ $payment->plan->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Purifier Type</dt>
                    <dd class="mt-1 text-gray-900">{{ ucfirst($payment->plan->purifier_type) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Duration</dt>
                    <dd class="mt-1 text-gray-900">{{ $payment->plan->duration_in_days }} days</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Litres</dt>
                    <dd class="mt-1 text-gray-900">{{ $payment->plan->litres }} L</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Consumption</dt>
                    <dd class="mt-1 text-gray-900">{{ $payment->consumption ?? 0 }} L</dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Purifier Details -->
    @if($payment->purifier)
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Purifier Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Serial Number</dt>
                    <dd class="mt-1 text-gray-900">{{ $payment->purifier->serial_number }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Model</dt>
                    <dd class="mt-1 text-gray-900">{{ $payment->purifier->model }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Installation Date</dt>
                    <dd class="mt-1 text-gray-900">{{ $payment->purifier->installation_date ? $payment->purifier->installation_date->format('M d, Y') : 'Not installed' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Service</dt>
                    <dd class="mt-1 text-gray-900">{{ $payment->purifier->last_service_date ? $payment->purifier->last_service_date->format('M d, Y') : 'No service yet' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Next Service</dt>
                    <dd class="mt-1 text-gray-900">{{ $payment->purifier->next_service_date ? $payment->purifier->next_service_date->format('M d, Y') : 'Not scheduled' }}</dd>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 