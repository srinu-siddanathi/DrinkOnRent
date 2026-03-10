@extends('admin.layouts.app')

@section('title', 'Purifiers')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-gray-900">Purifiers</h2>
            <!-- <a href="{{ route('admin.purifiers.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Purifier
                </a> -->
        </div>

        <div class="mt-4 overflow-x-auto -mx-4 sm:mx-0">
            <div class="inline-block min-w-full">
            <table class="w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Serial Number</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Model</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Subscription Status</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Next Service</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($purifiers as $purifier)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purifier->serial_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purifier->model }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $purifier->type === 'ro' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $purifier->type_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $purifier->status === 'available' ? 'bg-green-100 text-green-800' : 
                                       ($purifier->status === 'assigned' ? 'bg-blue-100 text-blue-800' : 
                                       ($purifier->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($purifier->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($purifier->customer)
                                <div class="flex flex-col">
                                    <span>{{ $purifier->customer->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $purifier->customer->phone }}</span>
                                </div>
                            @else
                                Not Assigned
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @php
                                $subscriptionCollection = $purifier->subscriptions->isNotEmpty()
                                    ? $purifier->subscriptions
                                    : collect(optional($purifier->customer)->subscriptions ?? []);

                                $displaySubscription = $subscriptionCollection
                                    ->where('status', 'active')
                                    ->sortByDesc('created_at')
                                    ->first() ?? $subscriptionCollection->sortByDesc('created_at')->first();
                            @endphp

                            @if($displaySubscription)
                                <div class="flex flex-col space-y-1">
                                    <span class="text-gray-900">{{ $displaySubscription->plan->name ?? 'Plan' }}</span>
                                    <span class="px-2 inline-flex w-fit text-xs leading-5 font-semibold rounded-full 
                                        {{ $displaySubscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                           ($displaySubscription->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($displaySubscription->status) }}
                                    </span>

                                    @if($displaySubscription->end_date)
                                        @php
                                            $daysLeftRaw = now()->diffInDays($displaySubscription->end_date, false);
                                            $daysLeft = (int) max(0, ceil($daysLeftRaw));
                                            $totalDays = ($displaySubscription->start_date && $displaySubscription->end_date)
                                                ? $displaySubscription->start_date->diffInDays($displaySubscription->end_date)
                                                : 0;
                                            $percentage = $totalDays > 0
                                                ? max(0, min(100, ($daysLeftRaw / $totalDays) * 100))
                                                : 0;
                                        @endphp
                                        <div class="mt-1">
                                            <div class="flex items-center">
                                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="h-full {{ $percentage > 20 ? 'bg-indigo-600' : 'bg-red-500' }} rounded-full" 
                                                         style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <span class="ml-2 text-xs {{ $daysLeft < 5 ? 'text-red-500 font-medium' : 'text-gray-600' }}">
                                                    {{ $daysLeft }} {{ Str::plural('day', $daysLeft) }} left
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-500">No subscription</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $purifier->next_service_date ? $purifier->next_service_date->format('Y-m-d') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.purifiers.show', $purifier) }}"
                                class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                            <a href="{{ route('admin.purifiers.edit', $purifier) }}"
                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $purifiers->links() }}
        </div>
    </div>
</div>
@endsection