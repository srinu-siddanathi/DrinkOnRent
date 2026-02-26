@extends('admin.layouts.app')

@section('title', 'New Complaint')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-6">New Complaint</h3>

        <form action="{{ route('admin.complaints.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="customer_id" class="block text-sm font-medium text-gray-700">
                    Customer <span class="text-red-500">*</span>
                </label>
                <select name="customer_id" id="customer_id"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('customer_id') border-red-500 @enderror"
                        required>
                    <option value="">Select a customer</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
                @error('customer_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700">
                    Subject <span class="text-red-500">*</span>
                </label>
                <input type="text" name="subject" id="subject"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('subject') border-red-500 @enderror"
                       value="{{ old('subject') }}"
                       placeholder="Enter the complaint subject"
                       required>
                @error('subject')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="details" class="block text-sm font-medium text-gray-700">
                    Complaint Details <span class="text-red-500">*</span>
                </label>
                <textarea name="details" id="details" rows="5"
                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('details') border-red-500 @enderror"
                          placeholder="Describe the complaint in detail"
                          required>{{ old('details') }}</textarea>
                @error('details')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.complaints.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Submit Complaint
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
