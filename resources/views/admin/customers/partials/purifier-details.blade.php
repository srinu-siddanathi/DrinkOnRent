<div class="flex justify-between items-center">
    <span class="text-sm text-gray-500">Model</span>
    <span class="text-sm font-medium text-gray-900">{{ $purifier->model }}</span>
</div>

<div class="flex justify-between items-center">
    <span class="text-sm text-gray-500">Installed On</span>
    <span class="text-sm text-gray-900">
        {{ $purifier->installation_date ? $purifier->installation_date->format('M d, Y') : 'Not installed' }}
    </span>
</div>

<div class="flex justify-between items-center">
    <span class="text-sm text-gray-500">Next Service</span>
    <div>
        @if($purifier->next_service_date)
            @php
                $daysToService = now()->diffInDays($purifier->next_service_date, false);
            @endphp
            <span class="px-2 py-1 text-xs font-medium rounded-full 
                {{ $daysToService < 5 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                {{ ceil($daysToService) }} days left
            </span>
        @else
            <span class="text-sm text-gray-500">Not scheduled</span>
        @endif
    </div>
</div>

@if($purifier->latitude && $purifier->longitude)
    <div class="pt-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-sm text-gray-500">Location</span>
            </div>
            <a href="https://www.google.com/maps?q={{ $purifier->latitude }},{{ $purifier->longitude }}" 
               target="_blank" class="text-sm text-indigo-600 hover:text-indigo-900">
                View on Maps
            </a>
        </div>
        @if($purifier->location_address)
            <p class="mt-2 text-sm text-gray-500">{{ $purifier->location_address }}</p>
        @endif
    </div>
@endif 