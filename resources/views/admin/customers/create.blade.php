@extends('admin.layouts.app')

@section('title', 'Register Customer')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Customer Form Section -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">Customer Information</h3>

                <form id="customerForm" action="{{ route('admin.customers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- First Name -->
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

                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" id="phone" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-500 @enderror"
                               value="{{ old('phone') }}"
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
                               value="{{ old('email') }}"
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
                                  required>{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Area -->
                    <div>
                        <label for="area" class="block text-sm font-medium text-gray-700">
                            Area <span class="text-red-500">*</span>
                        </label>
                        <select name="area" id="area" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('area') border-red-500 @enderror"
                                required>
                            <option value="">Select Area</option>
                            <option value="Pendurthi" {{ old('area') === 'Pendurthi' ? 'selected' : '' }}>Pendurthi</option>
                            <option value="Kothavalasa" {{ old('area') === 'Kothavalasa' ? 'selected' : '' }}>Kothavalasa</option>
                            <option value="Anakapelly" {{ old('area') === 'Anakapelly' ? 'selected' : '' }}>Anakapelly</option>
                            <option value="Chinna Musallwada" {{ old('area') === 'Chinna Musallwada' ? 'selected' : '' }}>Chinna Musallwada</option>
                            <option value="NAD Junction" {{ old('area') === 'NAD Junction' ? 'selected' : '' }}>NAD Junction</option>
                            <option value="Marripalem" {{ old('area') === 'Marripalem' ? 'selected' : '' }}>Marripalem</option>
                            <option value="Gajuwaka" {{ old('area') === 'Gajuwaka' ? 'selected' : '' }}>Gajuwaka</option>
                            <option value="Koramanapalalem" {{ old('area') === 'Koramanapalalem' ? 'selected' : '' }}>Koramanapalalem</option>
                            <option value="Duvvada" {{ old('area') === 'Duvvada' ? 'selected' : '' }}>Duvvada</option>
                            <option value="Kancherapalem" {{ old('area') === 'Kancherapalem' ? 'selected' : '' }}>Kancherapalem</option>
                            <option value="RTC Complex" {{ old('area') === 'RTC Complex' ? 'selected' : '' }}>RTC Complex</option>
                            <option value="Maddipalem" {{ old('area') === 'Maddipalem' ? 'selected' : '' }}>Maddipalem</option>
                            <option value="Madhuruwada" {{ old('area') === 'Madhuruwada' ? 'selected' : '' }}>Madhuruwada</option>
                            <option value="Endada" {{ old('area') === 'Endada' ? 'selected' : '' }}>Endada</option>
                            <option value="Hnumanthwada" {{ old('area') === 'Hnumanthwada' ? 'selected' : '' }}>Hnumanthwada</option>
                            <option value="Akkayapalam" {{ old('area') === 'Akkayapalam' ? 'selected' : '' }}>Akkayapalam</option>
                            <option value="PM Palem" {{ old('area') === 'PM Palem' ? 'selected' : '' }}>PM Palem</option>
                            <option value="Allipuram" {{ old('area') === 'Allipuram' ? 'selected' : '' }}>Allipuram</option>
                            <option value="Siripuram" {{ old('area') === 'Siripuram' ? 'selected' : '' }}>Siripuram</option>
                            <option value="Shulanager" {{ old('area') === 'Shulanager' ? 'selected' : '' }}>Shulanager</option>
                            <option value="Peddawaltair" {{ old('area') === 'Peddawaltair' ? 'selected' : '' }}>Peddawaltair</option>
                            <option value="Chinnawaltair" {{ old('area') === 'Chinnawaltair' ? 'selected' : '' }}>Chinnawaltair</option>
                        </select>
                        @error('area')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Next Service Reminder -->
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

                    <!-- ID Proof Upload -->
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

                    <!-- Date of Installation -->
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

                    <!-- Purifier Code -->
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

                    <!-- Purifier Type -->
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
                </form>
            </div>
        </div>

        <!-- Purifier Information Section -->
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
                                <p class="text-sm text-blue-700 font-medium">Registration Flow</p>
                                <ul class="text-sm text-blue-600 mt-2 space-y-1">
                                    <li>✓ Enter customer details on the left</li>
                                    <li>✓ Upload ID proof document</li>
                                    <li>✓ Enter purifier details below</li>
                                    <li>✓ Click "Register" to create both</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Info Box 2 -->
                    <div class="p-4 bg-green-50 border border-green-200 rounded-md">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-green-700 font-medium">What Happens Next</p>
                                <p class="text-sm text-green-600 mt-1">
                                    The purifier will be automatically assigned a unique serial number based on its type.
                                </p>
                            </div>
                        </div>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Register Customer & Purifier
        </button>
    </div>
@endsection

