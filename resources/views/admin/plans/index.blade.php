@extends('admin.layouts.app')

@section('title', 'Plans')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900">Plans</h2>
                <a href="{{ route('admin.plans.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Plan
                </a>
            </div>

            <div class="mt-4">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-purple-800 mb-4">RO Plans</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($plans->where('purifier_type', 'ro') as $plan)
                            <div class="border rounded-lg p-4 {{ $plan->is_active ? 'bg-white' : 'bg-gray-50' }}">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900">{{ $plan->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $plan->litres }} Litres</p>
                                        @if($plan->description)
                                            <p class="mt-1 text-sm text-gray-500">{{ $plan->description }}</p>
                                        @endif
                                    </div>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="mt-4">
                                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($plan->price, 2) }}</p>
                                    <p class="text-sm text-gray-500">{{ $plan->duration_in_days }} days</p>
                                </div>
                                <div class="mt-4 flex space-x-3">
                                    <a href="{{ route('admin.plans.edit', $plan) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    
                                    <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this plan?');" 
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No RO plans available.</p>
                        @endforelse
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-blue-800 mb-4">Alkaline Plans</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($plans->where('purifier_type', 'alkaline') as $plan)
                            <div class="border rounded-lg p-4 {{ $plan->is_active ? 'bg-white' : 'bg-gray-50' }}">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900">{{ $plan->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $plan->litres }} Litres</p>
                                        @if($plan->description)
                                            <p class="mt-1 text-sm text-gray-500">{{ $plan->description }}</p>
                                        @endif
                                    </div>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="mt-4">
                                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($plan->price, 2) }}</p>
                                    <p class="text-sm text-gray-500">{{ $plan->duration_in_days }} days</p>
                                </div>
                                <div class="mt-4 flex space-x-3">
                                    <a href="{{ route('admin.plans.edit', $plan) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    
                                    <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this plan?');" 
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No Alkaline plans available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 