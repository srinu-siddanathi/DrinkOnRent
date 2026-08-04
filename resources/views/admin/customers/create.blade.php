@extends('admin.layouts.app')

@section('title', 'Register Customer')

@section('content')
    <div class="max-w-6xl mx-auto">
        <form id="customerForm" action="{{ route('admin.customers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Customer Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="first_name" id="first_name"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('first_name') border-red-500 @enderror"
                                   value="{{ old('first_name') }}"
                                   placeholder="Enter full name"
                                   required>
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" id="phone"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-500 @enderror"
                                   value="{{ old('phone') }}"
                                   placeholder="10-digit phone number"
                                   inputmode="numeric"
                                   maxlength="10"
                                   minlength="10"
                                   pattern="[0-9]{10}"
                                   oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)"
                                   required>
                            @error('phone')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror"
                                   value="{{ old('email') }}"
                                   placeholder="customer@example.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700">
                                Address <span class="text-red-500">*</span>
                            </label>
                            <textarea name="address" id="address" rows="3"
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('address') border-red-500 @enderror"
                                      placeholder="Full address"
                                      required>{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Area and Service Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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
                                    <option value="{{ $area }}" {{ old('area') === $area ? 'selected' : '' }}>{{ $area }}</option>
                                @endforeach
                            </select>
                            @error('area')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="next_service_reminder" class="block text-sm font-medium text-gray-700">
                                Next Service Reminder <span class="text-red-500">*</span>
                            </label>
                            <select name="next_service_reminder" id="next_service_reminder"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('next_service_reminder') border-red-500 @enderror"
                                    required>
                                <option value="">Select Reminder Period</option>
                                <option value="3 Months" {{ old('next_service_reminder') === '3 Months' ? 'selected' : '' }}>3 Months</option>
                                <option value="6 Months" {{ old('next_service_reminder') === '6 Months' ? 'selected' : '' }}>6 Months</option>
                                <option value="12 Months" {{ old('next_service_reminder') === '12 Months' ? 'selected' : '' }}>12 Months</option>
                            </select>
                            @error('next_service_reminder')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Purifier and Documents</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="installation_date" class="block text-sm font-medium text-gray-700">
                                Date of Installation <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="installation_date" id="installation_date"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('installation_date') border-red-500 @enderror"
                                   value="{{ old('installation_date') }}"
                                   required>
                            @error('installation_date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="purifier_code" class="block text-sm font-medium text-gray-700">
                                Purifier Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="purifier_code" id="purifier_code"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('purifier_code') border-red-500 @enderror"
                                   value="{{ old('purifier_code') }}"
                                   placeholder="Enter purifier code"
                                   required>
                            @error('purifier_code')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="purifier_type" class="block text-sm font-medium text-gray-700">
                                Purifier Type <span class="text-red-500">*</span>
                            </label>
                            <select name="purifier_type" id="purifier_type"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('purifier_type') border-red-500 @enderror"
                                    required>
                                <option value="">Select Type</option>
                                <option value="ro" {{ old('purifier_type') === 'ro' ? 'selected' : '' }}>RO</option>
                                <option value="alkaline" {{ old('purifier_type') === 'alkaline' ? 'selected' : '' }}>Alkaline</option>
                            </select>
                            @error('purifier_type')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="id_proof" class="block text-sm font-medium text-gray-700">
                                ID Proof (PDF/JPG/PNG - Max 5MB) <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="id_proof" id="id_proof"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('id_proof') border-red-500 @enderror"
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   required>
                            <p class="mt-1 text-sm text-gray-500">Accepted formats: PDF, JPG, PNG (Max 5MB)</p>
                            @error('id_proof')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Register Customer & Purifier
        </button>
    </div>
@endsection

