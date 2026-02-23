@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem;">
        <div class="bg-blue-100 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6 flex items-center justify-between">
                <div>
                    <dt class="text-sm font-medium text-gray-600 truncate">
                        Total Customers
                    </dt>
                    <dd class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $totalCustomers }}
                    </dd>
                </div>
                <svg class="w-12 h-12 text-blue-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-green-100 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6 flex items-center justify-between">
                <div>
                    <dt class="text-sm font-medium text-gray-600 truncate">
                        Active Subscriptions
                    </dt>
                    <dd class="mt-2 text-3xl font-bold text-green-600">
                        {{ $activeSubscriptions }}
                    </dd>
                </div>
                <svg class="w-12 h-12 text-green-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-red-100 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6 flex items-center justify-between">
                <div>
                    <dt class="text-sm font-medium text-gray-600 truncate">
                        Inactive Subscriptions
                    </dt>
                    <dd class="mt-2 text-3xl font-bold text-red-600">
                        {{ $inactiveSubscriptions }}
                    </dd>
                </div>
                <svg class="w-12 h-12 text-red-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-purple-100 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6 flex items-center justify-between">
                <div>
                    <dt class="text-sm font-medium text-gray-600 truncate">
                        Total Revenue
                    </dt>
                    <dd class="mt-2 text-3xl font-bold text-gray-900">
                        ₹{{ number_format($totalRevenue, 2) }}
                    </dd>
                </div>
                <svg class="w-12 h-12 text-purple-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div style="margin-top: 2rem;">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Revenue</h3>
        <div class="bg-yellow-100 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6 flex items-center justify-between">
                <div>
                    <dt class="text-sm font-medium text-gray-600 truncate">
                        Today's Revenue
                    </dt>
                    <dd class="mt-2 text-3xl font-bold text-yellow-600">
                        ₹{{ number_format($todayRevenue, 2) }}
                    </dd>
                </div>
                <svg class="w-12 h-12 text-yellow-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
@endsection 