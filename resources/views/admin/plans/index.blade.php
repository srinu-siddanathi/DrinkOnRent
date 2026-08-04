@extends('admin.layouts.app')

@section('title', 'Plans')

@section('content')
    @php
        $roPlans = $plans->where('purifier_type', 'ro');
        $alkalinePlans = $plans->where('purifier_type', 'alkaline');
    @endphp

    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-medium text-gray-900">Plans</h2>
                <a href="{{ route('admin.plans.create') }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Plan
                </a>
            </div>

            <div class="space-y-8">
                <section class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-5 sm:p-6">
                    <div class="mb-5 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-emerald-900">RO Plans</h3>
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-800">
                            {{ $roPlans->count() }} plans
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($roPlans as $plan)
                            <article class="relative overflow-hidden rounded-xl border border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-teal-50 p-4 shadow-sm">
                                <div class="absolute inset-x-0 top-0 h-1 bg-emerald-300"></div>
                                <div class="flex justify-between items-start gap-3">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $plan->name }}</h4>
                                        @if($plan->description)
                                            <p class="mt-1 text-sm text-gray-600">{{ $plan->description }}</p>
                                        @endif
                                    </div>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>

                                <div class="mt-4 pt-4 border-t border-emerald-200/70">
                                    <p class="text-2xl font-bold text-emerald-900">₹{{ number_format($plan->price, 2) }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $plan->duration_in_days }} days</p>
                                </div>

                                <div class="mt-4 flex items-center gap-3 text-sm font-medium">
                                    <a href="{{ route('admin.plans.edit', $plan) }}" class="text-emerald-700 hover:text-emerald-900">Edit</a>
                                    <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this plan?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </article>
                        @empty
                            <div class="sm:col-span-2 lg:col-span-3 rounded-xl border border-dashed border-emerald-200 bg-white/80 px-4 py-8 text-center text-gray-500">
                                No RO plans available.
                            </div>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-2xl border border-sky-100 bg-sky-50/70 p-5 sm:p-6">
                    <div class="mb-5 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-sky-900">Alkaline Plans</h3>
                        <span class="inline-flex items-center rounded-full bg-sky-100 px-3 py-1 text-xs font-medium text-sky-800">
                            {{ $alkalinePlans->count() }} plans
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($alkalinePlans as $plan)
                            <article class="relative overflow-hidden rounded-xl border border-sky-200 bg-gradient-to-br from-sky-50 via-white to-cyan-50 p-4 shadow-sm">
                                <div class="absolute inset-x-0 top-0 h-1 bg-sky-300"></div>
                                <div class="flex justify-between items-start gap-3">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $plan->name }}</h4>
                                        @if($plan->description)
                                            <p class="mt-1 text-sm text-gray-600">{{ $plan->description }}</p>
                                        @endif
                                    </div>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>

                                <div class="mt-4 pt-4 border-t border-sky-200/70">
                                    <p class="text-2xl font-bold text-sky-900">₹{{ number_format($plan->price, 2) }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $plan->duration_in_days }} days</p>
                                </div>

                                <div class="mt-4 flex items-center gap-3 text-sm font-medium">
                                    <a href="{{ route('admin.plans.edit', $plan) }}" class="text-sky-700 hover:text-sky-900">Edit</a>
                                    <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this plan?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </article>
                        @empty
                            <div class="sm:col-span-2 lg:col-span-3 rounded-xl border border-dashed border-sky-200 bg-white/80 px-4 py-8 text-center text-gray-500">
                                No Alkaline plans available.
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection 