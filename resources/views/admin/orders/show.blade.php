@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Order #{{ $order->id }}</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to List</a>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Customer</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $order->customer->name }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Plan</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $order->plan->name }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Start Date</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $order->start_date?->format('Y-m-d') ?? '-' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">End Date</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $order->end_date?->format('Y-m-d') ?? '-' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Litres Remaining</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $order->litres_remaining }}</p>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900">Update Order</h3>
                <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="mt-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Order Status</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="active" {{ $order->status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="expired" {{ $order->status === 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Payment Status</label>
                            <select name="payment_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ $order->payment_status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 