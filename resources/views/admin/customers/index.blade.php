@extends('admin.layouts.app')

@section('title', 'Customers')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900">Customers</h2>
            
            <div class="mt-4">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active Plan</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purifiers</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscription Status</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customers as $customer)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->phone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php
                                    $activeSubscriptions = $customer->subscriptions
                                        ->where('status', 'active')
                                        ->where('end_date', '>', now());
                                @endphp
                                
                                @if($activeSubscriptions->isNotEmpty())
                                    <div class="flex flex-col space-y-1">
                                        @foreach($activeSubscriptions as $subscription)
                                            <div class="flex items-center">
                                                <span class="w-2 h-2 mr-2 rounded-full 
                                                    {{ $subscription->plan->purifier_type === 'ro' ? 'bg-purple-400' : 'bg-blue-400' }}">
                                                </span>
                                                <span>{{ $subscription->plan->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500">No active plan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($customer->purifiers->count() > 0)
                                    <div class="flex flex-col space-y-2">
                                        @foreach($customer->purifiers as $purifier)
                                            <div class="space-y-1">
                                                <span class="inline-flex items-center">
                                                    <span class="w-2 h-2 mr-2 rounded-full 
                                                        {{ $purifier->type === 'ro' ? 'bg-purple-400' : 'bg-blue-400' }}">
                                                    </span>
                                                    <span>{{ $purifier->serial_number }}</span>
                                                    <span class="ml-1 text-gray-500">({{ ucfirst($purifier->type) }})</span>
                                                </span>
                                                @if($purifier->latitude && $purifier->longitude)
                                                    <div class="text-xs text-gray-500 pl-4">
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            </svg>
                                                            <a href="https://www.google.com/maps?q={{ $purifier->latitude }},{{ $purifier->longitude }}" 
                                                               target="_blank"
                                                               class="text-indigo-600 hover:text-indigo-900">
                                                                View Location
                                                            </a>
                                                        </div>
                                                        @if($purifier->location_address)
                                                            <div class="mt-1 pl-5">{{ $purifier->location_address }}</div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="text-xs text-gray-500 pl-4">Location not set</div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $activeSubscriptions = $customer->subscriptions
                                        ->where('status', 'active')
                                        ->where('end_date', '>', now());
                                @endphp
                                
                                @forelse($activeSubscriptions as $subscription)
                                    <div class="mb-2 last:mb-0">
                                        <div class="text-sm text-gray-900">
                                            {{ $subscription->plan->name }}
                                            @if($subscription->purifier)
                                                <span class="text-xs text-gray-500">({{ $subscription->purifier->serial_number }})</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            @php
                                                $daysLeft = ceil(now()->floatDiffInDays($subscription->end_date));
                                                $totalDays = $subscription->start_date->floatDiffInDays($subscription->end_date);
                                                $percentage = max(0, min(100, ($daysLeft / $totalDays) * 100));
                                            @endphp
                                            <div class="flex items-center">
                                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="h-full {{ $percentage > 20 ? 'bg-indigo-600' : 'bg-red-500' }} rounded-full" 
                                                         style="width: {{ $percentage }}%">
                                                    </div>
                                                </div>
                                                <span class="ml-2 {{ $daysLeft < 5 ? 'text-red-500 font-medium' : '' }}">
                                                    {{ $daysLeft }} {{ Str::plural('day', $daysLeft) }} left
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <span class="text-gray-500 text-sm">No active subscriptions</span>
                                @endforelse
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
@endsection 