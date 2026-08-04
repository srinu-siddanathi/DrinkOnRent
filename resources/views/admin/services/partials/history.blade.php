<div id="historyFilterWrapper" class="mb-4 flex justify-end">
    <div class="w-full sm:w-56">
        <label for="historyYearFilter" class="block text-xs font-medium text-gray-600 mb-1">Filter by Year</label>
        <select id="historyYearFilter" onchange="applyHistoryYearFilter(this.value)"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <option value="">All Years</option>
            @foreach($availableYears as $year)
                <option value="{{ $year }}" {{ (string)$selectedYear === (string)$year ? 'selected' : '' }}>{{ $year }}</option>
            @endforeach
        </select>
    </div>
</div>

<div id="service-list">
    @forelse($services as $service)
    <a href="#" class="block mb-4" onclick="showServiceDetails({{ $service->id }}); return false;">
        <div class="bg-white border rounded-lg shadow-sm p-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <div class="bg-blue-500 rounded-full p-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Service #{{ $service->id }}</p>
                        <p class="text-sm text-gray-500">{{ $service->service_date->format('jS M Y g:i A') }} • Next: {{ $service->expiry_date->format('jS M Y g:i A') }}</p>
                    </div>
                </div>
                <div>
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </div>
        </div>
    </a>
    @empty
    <div class="text-center py-4">
        <p class="text-gray-500">No service history found.</p>
    </div>
    @endforelse
</div>

@foreach($services as $service)
<div id="service-details-{{ $service->id }}" class="hidden">
    <div class="flex items-center mb-6">
        <button onclick="showServiceList()" class="text-indigo-600 hover:text-indigo-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <h2 class="text-lg font-medium text-gray-900 ml-4">Service</h2>
    </div>

    <div class="space-y-6">
        <!-- Service Details -->
        <div class="border rounded-lg p-4">
            <h3 class="text-md font-semibold text-gray-800 mb-4">Service details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Service date</p>
                    <p class="text-sm font-medium text-gray-900">{{ $service->service_date->format('jS M Y g:i A') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="text-sm font-medium text-gray-900">Completed</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Next service reminder</p>
                    <p class="text-sm font-medium text-gray-900">{{ $service->next_service_reminder }} Months</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Next service date</p>
                    <p class="text-sm font-medium text-gray-900">{{ $service->expiry_date->format('jS M Y g:i A') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Customer name</p>
                    <p class="text-sm font-medium text-gray-900">{{ $service->customer->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Phone number</p>
                    <p class="text-sm font-medium text-gray-900">{{ $service->customer->phone }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Area</p>
                    <p class="text-sm font-medium text-gray-900">{{ $service->customer->area ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Created at</p>
                    <p class="text-sm font-medium text-gray-900">{{ $service->created_at->format('jS M Y g:i A') }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">Address</p>
                    <p class="text-sm font-medium text-gray-900">{{ $service->customer->address }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">Spare parts used</p>
                    @if(!empty($service->spare_parts) && count($service->spare_parts) > 0)
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($service->spare_parts as $part)
                                <span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-800 rounded-full">{{ $part }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm font-medium text-gray-900">No spare parts recorded</p>
                    @endif
                </div>
            </div>
        </div>

    @if(!empty($service->images))
        <div class="service-gallery mt-3 flex gap-2 overflow-x-auto" data-service-id="{{ $service->id }}">
            @foreach($service->images as $image)
                <a href="{{ asset('uploads/service-images/' . $image) }}" class="block" title="Service #{{ $service->id }}">
                    <img src="{{ asset('uploads/service-images/' . $image) }}" alt="Service Image" class="h-16 w-16 object-cover rounded">
                </a>
            @endforeach
        </div>
    @endif
    </div>
</div>
@endforeach
