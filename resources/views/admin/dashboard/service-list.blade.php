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
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">WhatsApp Reminder</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($services as $service)
                            @php
                                $daysDifference = now()->startOfDay()->diffInDays($service->expiry_date->copy()->startOfDay(), false);
                                $daysText = $mode === 'soon' ? ($daysDifference . 'd') : (abs($daysDifference) . 'd');
                                $rawPhone = optional($service->customer)->phone ?? '';
                                $phoneDigits = preg_replace('/\D+/', '', $rawPhone);
                                $whatsAppPhone = strlen($phoneDigits) === 10 ? '91' . $phoneDigits : $phoneDigits;
                                $customerName = optional($service->customer)->name ?? 'Customer';
                                $expiryText = $service->expiry_date->format('jS M Y');
                                $message = $mode === 'soon'
                                    ? "Hi {$customerName}, just a reminder from DrinkOnRent: your purifier service is due on {$expiryText}. Please confirm a convenient slot for service."
                                    : "Hi {$customerName}, just a reminder from DrinkOnRent: your purifier service was due on {$expiryText}. Please confirm a convenient slot for service.";
                                $whatsAppUrl = $whatsAppPhone ? 'https://wa.me/' . $whatsAppPhone . '?text=' . urlencode($message) : null;
                                $hasWhatsAppPhone = !empty($whatsAppPhone);
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-700">{{ optional($service->customer)->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($service->customer)->phone ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($service->customer)->area ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $service->next_service_reminder }}M</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $service->expiry_date->format('jS M Y g:i A') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $mode === 'soon' ? 'text-orange-600' : 'text-red-600' }}">{{ $daysText }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a
                                        href="{{ $whatsAppUrl ?: '#' }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-2 rounded-md px-3 py-1.5 text-xs font-semibold text-white {{ $hasWhatsAppPhone ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 cursor-not-allowed pointer-events-none' }}"
                                        title="{{ $hasWhatsAppPhone ? 'Send WhatsApp reminder' : 'Customer phone number not available' }}"
                                        aria-disabled="{{ $hasWhatsAppPhone ? 'false' : 'true' }}"
                                    >
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.149-.198.297-.768.967-.94 1.164-.173.198-.347.223-.644.075-.297-.149-1.255-.463-2.39-1.475-.883-.787-1.479-1.76-1.652-2.057-.173-.297-.018-.458.13-.606.133-.132.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.372-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.273.297-1.04 1.016-1.04 2.48s1.065 2.877 1.213 3.075c.149.198 2.095 3.2 5.078 4.487.71.306 1.263.489 1.694.626.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.096h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374A9.86 9.86 0 0 1 2.17 11.99c.003-5.447 4.43-9.872 9.884-9.872a9.83 9.83 0 0 1 6.988 2.892 9.82 9.82 0 0 1 2.893 6.99c-.003 5.447-4.43 9.872-9.884 9.872"/>
                                        </svg>
                                        Remind
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
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
