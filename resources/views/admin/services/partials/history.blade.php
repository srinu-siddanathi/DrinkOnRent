<div id="service-list">
    @forelse($services as $service)
    <a href="#" class="block mb-4" onclick="showServiceDetails({{ $service->id }})">
        <div class="bg-white border rounded-lg shadow-sm p-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <div class="bg-blue-500 rounded-full p-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Service</p>
                        <p class="text-sm text-gray-500">{{ $service->service_date->format('d M Y') }}</p>
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
                    <p class="text-sm font-medium text-gray-900">{{ $service->service_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="text-sm font-medium text-gray-900">Completed</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Customer name</p>
                    <p class="text-sm font-medium text-gray-900">{{ $service->customer->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Phone number</p>
                    <p class="text-sm font-medium text-gray-900">{{ $service->customer->phone }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">Address</p>
                    <p class="text-sm font-medium text-gray-900">{{ $service->customer->address }}</p>
                </div>
            </div>
        </div>

         @if($service->images)
        <div class="service-gallery mt-3 flex gap-2 overflow-x-auto">
            @foreach($service->images as $image)
                <a href="{{ asset('uploads/service-images/' . $image) }}">
                    <img src="{{ asset('uploads/service-images/' . $image) }}" alt="Service Image" class="h-16 w-16 object-cover rounded">
                </a>
            @endforeach
        </div>
    @endif
    </div>
</div>
@endforeach
