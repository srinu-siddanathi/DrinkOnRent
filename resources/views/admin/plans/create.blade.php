@extends('admin.layouts.app')

@section('title', 'Create Plan')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900">Create New Plan</h2>
                <a href="{{ route('admin.plans.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to List</a>
            </div>

            <form action="{{ route('admin.plans.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Plan Name</label>
                        <input type="text" name="name" id="name" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               value="{{ old('name') }}" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="purifier_type" class="block text-sm font-medium text-gray-700">Purifier Type</label>
                        <select name="purifier_type" id="purifier_type" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="ro" {{ old('purifier_type') === 'ro' ? 'selected' : '' }}>RO</option>
                            <option value="alkaline" {{ old('purifier_type') === 'alkaline' ? 'selected' : '' }}>Alkaline</option>
                        </select>
                        @error('purifier_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="litres" class="block text-sm font-medium text-gray-700">Litres</label>
                        <input type="number" name="litres" id="litres" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               value="{{ old('litres') }}" required min="1">
                        @error('litres')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price (₹)</label>
                        <input type="number" name="price" id="price" step="0.01"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               value="{{ old('price') }}" required min="0">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration_in_days" class="block text-sm font-medium text-gray-700">Duration (Days)</label>
                        <input type="number" name="duration_in_days" id="duration_in_days" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               value="{{ old('duration_in_days') }}" required min="1">
                        @error('duration_in_days')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1" 
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                                       {{ old('is_active') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Active</span>
                            </label>
                        </div>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection 