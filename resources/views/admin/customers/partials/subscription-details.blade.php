@if($subscription->purifier)
    <div class="flex justify-between items-center">
        <span class="text-sm text-gray-500">Purifier</span>
        <span class="text-sm font-medium text-gray-900">
            {{ $subscription->purifier->serial_number }}
        </span>
    </div>
@endif

<div>
    @php
        $daysLeft = ceil(now()->floatDiffInDays($subscription->end_date));
        $totalDays = $subscription->start_date->floatDiffInDays($subscription->end_date);
        $percentage = max(0, min(100, ($daysLeft / $totalDays) * 100));
    @endphp
    <div class="flex items-center justify-between mb-2">
        <span class="text-sm text-gray-500">Validity</span>
        <span class="text-sm {{ $daysLeft < 5 ? 'text-red-600 font-medium' : 'text-gray-900' }}">
            {{ $daysLeft }} {{ Str::plural('day', $daysLeft) }} left
        </span>
    </div>
    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
        <div class="h-full {{ $percentage > 20 ? 'bg-indigo-600' : 'bg-red-500' }} rounded-full" 
             style="width: {{ $percentage }}%">
        </div>
    </div>
</div>

<div class="pt-4 border-t border-gray-200">
    <div class="flex justify-between items-center">
        <span class="text-sm text-gray-500">Water Consumption</span>
        <span class="text-sm font-medium text-gray-900">
            {{ $subscription->litres_consumed }}/{{ $subscription->plan->litres }} L
        </span>
    </div>
</div> 