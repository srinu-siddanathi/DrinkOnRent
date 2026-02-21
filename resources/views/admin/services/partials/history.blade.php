@forelse($services as $service)
<div class="bg-white border rounded-lg shadow-sm p-4 mb-4">
    <div class="flex justify-between items-start">
        <div>
            <p class="text-sm font-medium text-gray-900">Service Date: {{ $service->service_date->format('d M Y') }}</p>
            <p class="text-sm text-gray-500">Next Reminder: {{ $service->next_service_reminder }} Months</p>
            @if($service->spare_parts)
                <p class="text-sm text-gray-500 mt-1">Parts: {{ implode(', ', $service->spare_parts) }}</p>
            @endif
        </div>
        <div class="text-right">
            <p class="text-sm font-bold text-gray-900">₹{{ number_format($service->total_amount, 2) }}</p>
            <p class="text-xs text-gray-500">{{ $service->payment_mode }}</p>
        </div>
    </div>
    @if($service->images)
        <div class="mt-3 flex gap-2 overflow-x-auto">
            @foreach($service->images as $image)
                <a href="{{ Storage::url($image) }}" target="_blank">
                    <img src="{{ Storage::url($image) }}" alt="Service Image" class="h-16 w-16 object-cover rounded">
                </a>
            @endforeach
        </div>
    @endif
</div>
@empty
<div class="text-center py-4">
    <p class="text-gray-500">No service history found.</p>
</div>
@endforelse
