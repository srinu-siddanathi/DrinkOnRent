@extends('admin.layouts.app')

@section('title', 'Edit Purifier')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900">Edit Purifier</h2>
                <a href="{{ route('admin.purifiers.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to List</a>
            </div>

            <form action="{{ route('admin.purifiers.update', $purifier) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="serial_number" class="block text-sm font-medium text-gray-700">Serial Number</label>
                        <input type="text" name="serial_number" id="serial_number" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               value="{{ old('serial_number', $purifier->serial_number) }}" required>
                        @error('serial_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                        <input type="text" name="model" id="model" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               value="{{ old('model', $purifier->model) }}" required>
                        @error('model')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                        <select name="type" id="type" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="ro" {{ old('type', $purifier->type) === 'ro' ? 'selected' : '' }}>RO</option>
                            <option value="alkaline" {{ old('type', $purifier->type) === 'alkaline' ? 'selected' : '' }}>Alkaline</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="available" {{ old('status', $purifier->status) === 'available' ? 'selected' : '' }}>Available</option>
                            <option value="assigned" {{ old('status', $purifier->status) === 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="maintenance" {{ old('status', $purifier->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="retired" {{ old('status', $purifier->status) === 'retired' ? 'selected' : '' }}>Retired</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
                        <select name="customer_id" id="customer_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id', $purifier->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="installation_date" class="block text-sm font-medium text-gray-700">Installation Date</label>
                        <input type="date" name="installation_date" id="installation_date" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               value="{{ old('installation_date', $purifier->installation_date?->format('Y-m-d')) }}">
                        @error('installation_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="next_service_date" class="block text-sm font-medium text-gray-700">Next Service Date</label>
                        <input type="date" name="next_service_date" id="next_service_date" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               value="{{ old('next_service_date', $purifier->next_service_date?->format('Y-m-d')) }}">
                        @error('next_service_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Purifier
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection 