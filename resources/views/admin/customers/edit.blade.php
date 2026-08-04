@extends('admin.layouts.app')

@section('title', 'Edit Customer')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Customer Form Section -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">Edit Customer Information</h3>

                <form id="customerForm" action="{{ route('admin.customers.update', $customer) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="first_name" id="first_name" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('first_name') border-red-500 @enderror"
                               value="{{ old('first_name', $customer->first_name) }}"
                               placeholder="Enter full name"
                               required>
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" id="phone" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-500 @enderror"
                               value="{{ old('phone', $customer->phone) }}"
                               placeholder="10-digit phone number"
                               pattern="[0-9]{10}"
                               required>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email
                        </label>
                        <input type="email" name="email" id="email" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror"
                               value="{{ old('email', $customer->email) }}"
                               placeholder="customer@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">
                            Address <span class="text-red-500">*</span>
                        </label>
                        <textarea name="address" id="address" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('address') border-red-500 @enderror"
                                  placeholder="Full address"
                                  required>{{ old('address', $customer->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Area -->
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="area" class="block text-sm font-medium text-gray-700">
                                Area <span class="text-red-500">*</span>
                            </label>
                            <a href="{{ route('admin.settings.masters.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">Manage Areas</a>
                        </div>
                        <select name="area" id="area" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('area') border-red-500 @enderror"
                                required>
                            <option value="">Select Area</option>
                            @foreach($areas as $area)
                                <option value="{{ $area }}" {{ old('area', $customer->area) === $area ? 'selected' : '' }}>{{ $area }}</option>
                            @endforeach
                        </select>
                        @error('area')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ID Proof Upload -->
                    <div>
                        <label for="id_proof" class="block text-sm font-medium text-gray-700">
                            ID Proof (PDF/JPG/PNG - Max 5MB)
                        </label>
                        @if($customer->id_proof)
                            <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-md">
                                <p class="text-sm text-blue-700">
                                    Current ID Proof: <a href="{{ route('admin.customers.download-id-proof', $customer) }}" class="font-semibold hover:underline">Download</a>
                                </p>
                            </div>
                        @endif
                        <input type="file" name="id_proof" id="id_proof" 
                               class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('id_proof') border-red-500 @enderror"
                               accept=".pdf,.jpg,.jpeg,.png">
                        <p class="mt-1 text-sm text-gray-500">Leave empty to keep current file. Accepted formats: PDF, JPG, PNG (Max 5MB)</p>
                        @error('id_proof')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date of Installation -->
                    @php
                        $purifier = $customer->purifiers->first();
                        $installationDate = $purifier && $purifier->installation_date ? $purifier->installation_date->format('Y-m-d') : '';
                    @endphp
                    <div>
                        <label for="installation_date" class="block text-sm font-medium text-gray-700">
                            Date of Installation
                        </label>
                        <input type="date" name="installation_date" id="installation_date" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('installation_date') border-red-500 @enderror"
                               value="{{ old('installation_date', $installationDate) }}">
                        <p class="mt-1 text-sm text-gray-500">Leave empty to keep current date</p>
                        @error('installation_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Purifier Code -->
                    <div>
                        <label for="purifier_code" class="block text-sm font-medium text-gray-700">
                            Purifier Code
                        </label>
                        <input type="text" name="purifier_code" id="purifier_code" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('purifier_code') border-red-500 @enderror"
                               value="{{ old('purifier_code', $purifier ? $purifier->purifier_code : '') }}"
                               placeholder="Enter purifier code">
                        <p class="mt-1 text-sm text-gray-500">Leave empty to keep current code</p>
                        @error('purifier_code')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Purifier Type -->
                    <div>
                        <label for="purifier_type" class="block text-sm font-medium text-gray-700">
                            Purifier Type
                        </label>
                        <select name="purifier_type" id="purifier_type" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('purifier_type') border-red-500 @enderror">
                            <option value="">Keep Current</option>
                            <option value="ro" {{ old('purifier_type', $purifier ? $purifier->type : '') === 'ro' ? 'selected' : '' }}>RO</option>
                            <option value="alkaline" {{ old('purifier_type', $purifier ? $purifier->type : '') === 'alkaline' ? 'selected' : '' }}>Alkaline</option>
                        </select>
                        @error('purifier_type')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">Summary</h3>

                <div class="space-y-6">
                    <!-- Info Box -->
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-md">
                        <div class="flex">
                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700 font-medium">Edit Information</p>
                                <ul class="text-sm text-blue-600 mt-2 space-y-1">
                                    <li>✓ Update customer details as needed</li>
                                    <li>✓ Upload new ID proof document (optional)</li>
                                    <li>✓ Update purifier information (optional)</li>
                                    <li>✓ Click "Update" to save changes</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Details Box -->
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-md">
                        <p class="text-sm font-medium text-gray-900 mb-3">Current Information</p>
                        <dl class="space-y-2 text-sm">
                            <div>
                                <dt class="text-gray-600 font-medium">Phone:</dt>
                                <dd class="text-gray-900">{{ $customer->phone }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-600 font-medium">Area:</dt>
                                <dd class="text-gray-900">{{ $customer->area }}</dd>
                            </div>
                            @if($purifier)
                                <div>
                                    <dt class="text-gray-600 font-medium">Purifier Serial:</dt>
                                    <dd class="text-gray-900">{{ $purifier->serial_number }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-600 font-medium">Purifier Type:</dt>
                                    <dd class="text-gray-900">{{ ucfirst($purifier->type) }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex justify-between items-center">
        <a href="{{ route('admin.customers.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Customers
        </a>

        <button type="submit" form="customerForm" 
                class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Update Customer
        </button>
    </div>
@endsection
