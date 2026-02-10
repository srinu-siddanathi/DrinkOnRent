@extends('admin.layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="space-y-6">
    <!-- Customer Info Card -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium text-gray-900">Customer Information</h3>
                <span
                    class="px-3 py-1 text-xs font-medium rounded-full 
                    {{ $customer->is_phone_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $customer->is_phone_verified ? 'Verified' : 'Unverified' }}
                </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col space-y-1">
                    <span class="text-sm text-gray-500">Name</span>
                    <span class="text-base text-gray-900">{{ $customer->name }}</span>
                </div>
                <div class="flex flex-col space-y-1">
                    <span class="text-sm text-gray-500">Email</span>
                    <span class="text-base text-gray-900">{{ $customer->email }}</span>
                </div>
                <div class="flex flex-col space-y-1">
                    <span class="text-sm text-gray-500">Phone</span>
                    <span class="text-base text-gray-900">{{ $customer->phone }}</span>
                </div>
                <div class="flex flex-col space-y-1">
                    <span class="text-sm text-gray-500">Address</span>
                    <span class="text-base text-gray-900">{{ $customer->address }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Purifiers and Subscriptions Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Purifiers Section -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Purifiers</h3>
                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                    Total: {{ $customer->purifiers->count() }}
                </span>
            </div>

            @if($customer->purifiers->count() > 0)
            <div class="space-y-4">
                @foreach($customer->purifiers as $purifier)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <!-- Purifier Card Content (same as before) -->
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="w-3 h-3 rounded-full 
                                            {{ $purifier->type === 'ro' ? 'bg-purple-400' : 'bg-blue-400' }}">
                                </span>
                                <h4 class="text-base font-medium text-gray-900">{{ $purifier->serial_number }}</h4>
                            </div>
                            <span class="text-sm text-gray-500">{{ ucfirst($purifier->type) }}</span>
                        </div>
                    </div>

                    <div class="p-4 space-y-4">
                        <!-- Rest of the purifier card content remains the same -->
                        @include('admin.customers.partials.purifier-details', ['purifier' => $purifier])
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <p class="text-gray-500">No purifiers assigned</p>
            </div>
            @endif
        </div>

        <!-- Subscriptions Section -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Active Subscriptions</h3>
                @php
                $activeSubscriptions = $customer->subscriptions
                ->where('status', 'active')
                ->where('end_date', '>', now());
                @endphp
                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                    Active: {{ $activeSubscriptions->count() }}
                </span>
            </div>

            @if($activeSubscriptions->isNotEmpty())
            <div class="space-y-4">
                @foreach($activeSubscriptions as $subscription)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <!-- Subscription Card Content (same as before) -->
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h4 class="text-base font-medium text-gray-900">{{ $subscription->plan->name }}</h4>
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800">
                                ₹{{ number_format($subscription->plan->price) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-4 space-y-4">
                        <!-- Rest of the subscription card content remains the same -->
                        @include('admin.customers.partials.subscription-details', ['subscription' => $subscription])
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <p class="text-gray-500">No active subscriptions</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection