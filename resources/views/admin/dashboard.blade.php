@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">
                    Total Customers
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    {{ $totalCustomers }}
                </dd>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">
                    Active Subscriptions
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    {{ $activeSubscriptions }}
                </dd>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">
                    Total Revenue
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    ₹{{ number_format($totalRevenue, 2) }}
                </dd>
            </div>
        </div>
    </div>
@endsection 