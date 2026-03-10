@extends('admin.layouts.app')

@section('title', 'Purifier Details')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-medium text-gray-900">Purifier Details</h2>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.purifiers.edit', $purifier) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Edit
                    </a>
                    <a href="{{ route('admin.purifiers.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Back to List
                    </a>
                </div>
            </div>

            <div class="border-t border-gray-200 px-4 py-5">
                <div class="grid grid-cols-2 gap-4">
                    @php
                        $subscriptionCollection = $purifier->subscriptions->isNotEmpty()
                            ? $purifier->subscriptions
                            : collect(optional($purifier->customer)->subscriptions ?? []);

                        $displaySubscription = $subscriptionCollection
                            ->where('status', 'active')
                            ->sortByDesc('created_at')
                            ->first() ?? $subscriptionCollection->sortByDesc('created_at')->first();
                    @endphp

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Serial Number</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $purifier->serial_number }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Model</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $purifier->model }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Status</h3>
                        <p class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $purifier->status === 'available' ? 'bg-green-100 text-green-800' : 
                                   ($purifier->status === 'assigned' ? 'bg-blue-100 text-blue-800' : 
                                   ($purifier->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($purifier->status) }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Customer</h3>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($purifier->customer)
                                <a href="{{ route('admin.customers.show', $purifier->customer) }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $purifier->customer->name }}
                                </a>
                                <span class="block text-xs text-gray-500 mt-1">{{ $purifier->customer->phone }}</span>
                            @else
                                Not Assigned
                            @endif
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Installation Date</h3>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $purifier->installation_date ? $purifier->installation_date->format('Y-m-d') : '-' }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Last Service Date</h3>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $purifier->last_service_date ? $purifier->last_service_date->format('Y-m-d') : '-' }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Next Service Date</h3>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $purifier->next_service_date ? $purifier->next_service_date->format('Y-m-d') : '-' }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Type</h3>
                        <p class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $purifier->type === 'ro' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $purifier->type_name }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Created At</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $purifier->created_at->format('Y-m-d H:i') }}</p>
                    </div>

                    <div class="col-span-2">
                        <h3 class="text-sm font-medium text-gray-500">Subscription Status</h3>
                        @if($displaySubscription)
                            @php
                                $daysLeftRaw = $displaySubscription->end_date
                                    ? now()->diffInDays($displaySubscription->end_date, false)
                                    : 0;
                                $daysLeft = (int) max(0, ceil($daysLeftRaw));
                                $totalDays = ($displaySubscription->start_date && $displaySubscription->end_date)
                                    ? $displaySubscription->start_date->diffInDays($displaySubscription->end_date)
                                    : 0;
                                $percentage = $totalDays > 0
                                    ? max(0, min(100, ($daysLeftRaw / $totalDays) * 100))
                                    : 0;
                            @endphp
                            <div class="mt-1 flex items-center gap-3">
                                <span class="text-sm text-gray-900">{{ $displaySubscription->plan->name ?? 'Plan' }}</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $displaySubscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($displaySubscription->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($displaySubscription->status) }}
                                </span>
                            </div>
                            @if($displaySubscription->end_date)
                                <div class="mt-2 max-w-md">
                                    <div class="flex items-center">
                                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full {{ $percentage > 20 ? 'bg-indigo-600' : 'bg-red-500' }} rounded-full" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="ml-2 text-xs {{ $daysLeft < 5 ? 'text-red-500 font-medium' : 'text-gray-600' }}">
                                            {{ $daysLeft }} {{ Str::plural('day', $daysLeft) }} left
                                        </span>
                                    </div>
                                </div>
                            @endif
                        @else
                            <p class="mt-1 text-sm text-gray-500">No subscription linked.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 px-4 py-5">
                <h3 class="text-base font-medium text-gray-900 mb-4">Payment History</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                                <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($payments->isNotEmpty())
                                @foreach($payments as $payment)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $payment->subscription->plan->name ?? '-' }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                               ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $payment->razorpay_order_id ?? '-' }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $payment->razorpay_payment_id ?? '-' }}</td>
                                </tr>
                                @endforeach
                            @elseif(isset($fallbackPayments) && $fallbackPayments->isNotEmpty())
                                @foreach($fallbackPayments as $payment)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $payment->plan_name }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                               ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">-</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">-</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-sm text-gray-500 text-center">No payment history available for this purifier.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection 