@extends('admin.layouts.app')

@section('title', 'Purifier Request Details')

@section('content')
<div class="space-y-6">
    <!-- Request Info Card -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium text-gray-900">Request Details</h3>
                <span class="px-3 py-1 text-xs font-medium rounded-full 
                    @switch($purifierRequest->status)
                        @case('pending') bg-yellow-100 text-yellow-800 @break
                        @case('approved') bg-green-100 text-green-800 @break
                        @case('rejected') bg-red-100 text-red-800 @break
                        @case('in_progress') bg-blue-100 text-blue-800 @break
                        @case('completed') bg-gray-100 text-gray-800 @break
                    @endswitch
                ">
                    {{ ucfirst(str_replace('_', ' ', $purifierRequest->status)) }}
                </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col space-y-1">
                    <span class="text-sm text-gray-500">Customer</span>
                    <span class="text-base text-gray-900">{{ $purifierRequest->customer->name }}</span>
                </div>
                <div class="flex flex-col space-y-1">
                    <span class="text-sm text-gray-500">Purifier Type</span>
                    <span class="text-base text-gray-900">{{ ucfirst($purifierRequest->purifier_type) }}</span>
                </div>
                <div class="flex flex-col space-y-1">
                    <span class="text-sm text-gray-500">Requested At</span>
                    <span class="text-base text-gray-900">{{ $purifierRequest->created_at->format('d M, Y H:i A') }}</span>
                </div>
                <div class="flex flex-col space-y-1">
                    <span class="text-sm text-gray-500">Location Address</span>
                    <span class="text-base text-gray-900">{{ $purifierRequest->location_address }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Map and Actions Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Map Section -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Location Map</h3>
                <div id="map" style="height: 400px;" class="rounded-lg"></div>
                <p class="mt-2 text-sm text-gray-500">
                    <a href="https://www.google.com/maps?q={{ $purifierRequest->latitude }},{{ $purifierRequest->longitude }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                        Open in Google Maps
                    </a>
                </p>
            </div>
        </div>

        <!-- Actions Section -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Status</h3>
                <form action="{{ route('admin.purifier-requests.update', $purifierRequest) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="pending" @if($purifierRequest->status == 'pending') selected @endif>Pending</option>
                            <option value="approved" @if($purifierRequest->status == 'approved') selected @endif>Approved</option>
                            <option value="in_progress" @if($purifierRequest->status == 'in_progress') selected @endif>In Progress</option>
                            <option value="completed" @if($purifierRequest->status == 'completed') selected @endif>Completed</option>
                            <option value="rejected" @if($purifierRequest->.status == 'rejected') selected @endif>Rejected</option>
                        </select>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&callback=initMap" async defer></script>
<script>
    function initMap() {
        var location = { lat: {{ $purifierRequest->latitude }}, lng: {{ $purifierRequest->longitude }} };
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: location
        });
        var marker = new google.maps.Marker({
            position: location,
            map: map
        });
    }
</script>
@endsection
