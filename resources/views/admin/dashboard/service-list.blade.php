@extends('admin.layouts.app')

@section('title', $title)

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-900">{{ $title }}</h2>
    <p class="mt-2 text-2xl text-gray-600">{{ $subtitle }}</p>
</div>

<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="mt-1 overflow-x-auto -mx-4 sm:mx-0">
            <div class="inline-block min-w-full">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-blue-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Area</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Next Reminder</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Expire Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $mode === 'soon' ? 'Days Left' : 'Days Delayed' }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($services as $service)
                            @php
                                $daysDifference = now()->startOfDay()->diffInDays($service->expiry_date->copy()->startOfDay(), false);
                                $daysText = $mode === 'soon' ? ($daysDifference . 'd') : (abs($daysDifference) . 'd');
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-700">{{ optional($service->customer)->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($service->customer)->phone ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($service->customer)->area ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $service->next_service_reminder }}M</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $service->expiry_date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $mode === 'soon' ? 'text-orange-600' : 'text-red-600' }}">{{ $daysText }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                    {{ $mode === 'soon' ? 'No services are expiring within 7 days.' : 'No delayed services found.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $services->links() }}
        </div>
    </div>
</div>
@endsection
