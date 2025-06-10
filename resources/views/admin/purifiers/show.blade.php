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
                </div>
            </div>
        </div>
    </div>
@endsection 