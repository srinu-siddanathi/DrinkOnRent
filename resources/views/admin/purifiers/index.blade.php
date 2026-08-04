@extends('admin.layouts.app')

@section('title', 'Purifiers')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-gray-900">Purifiers</h2>
            <!-- <a href="{{ route('admin.purifiers.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Purifier
                </a> -->
        </div>

        <div class="mb-6">
            <div class="relative">
                <input
                    type="text"
                    id="purifierSearchInput"
                    placeholder="Search by serial, model, type, status, customer name or mobile..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                />
                <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <div class="mt-4 overflow-x-auto -mx-4 sm:mx-0">
            <div class="inline-block min-w-full">
            <table class="w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Serial Number</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Model</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Subscription Status</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Next Service</th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="purifierTableBody">
                    @foreach($purifiers as $purifier)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purifier->serial_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purifier->model }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $purifier->type === 'ro' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $purifier->type_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $purifier->status === 'available' ? 'bg-green-100 text-green-800' : 
                                       ($purifier->status === 'assigned' ? 'bg-blue-100 text-blue-800' : 
                                       ($purifier->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($purifier->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($purifier->customer)
                                <div class="flex flex-col">
                                    <span>{{ $purifier->customer->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $purifier->customer->phone }}</span>
                                </div>
                            @else
                                Not Assigned
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @php
                                $subscriptionCollection = $purifier->subscriptions->isNotEmpty()
                                    ? $purifier->subscriptions
                                    : collect(optional($purifier->customer)->subscriptions ?? []);

                                $displaySubscription = $subscriptionCollection
                                    ->where('status', 'active')
                                    ->sortByDesc('created_at')
                                    ->first() ?? $subscriptionCollection->sortByDesc('created_at')->first();
                            @endphp

                            @if($displaySubscription)
                                <div class="flex flex-col space-y-1">
                                    <span class="text-gray-900">{{ $displaySubscription->plan->name ?? 'Plan' }}</span>
                                    <span class="px-2 inline-flex w-fit text-xs leading-5 font-semibold rounded-full 
                                        {{ $displaySubscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                           ($displaySubscription->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($displaySubscription->status) }}
                                    </span>

                                    @if($displaySubscription->end_date)
                                        @php
                                            $daysLeftRaw = now()->diffInDays($displaySubscription->end_date, false);
                                            $daysLeft = (int) max(0, ceil($daysLeftRaw));
                                            $totalDays = ($displaySubscription->start_date && $displaySubscription->end_date)
                                                ? $displaySubscription->start_date->diffInDays($displaySubscription->end_date)
                                                : 0;
                                            $percentage = $totalDays > 0
                                                ? max(0, min(100, ($daysLeftRaw / $totalDays) * 100))
                                                : 0;
                                        @endphp
                                        <div class="mt-1">
                                            <div class="flex items-center">
                                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="h-full {{ $percentage > 20 ? 'bg-indigo-600' : 'bg-red-500' }} rounded-full" 
                                                         style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <span class="ml-2 text-xs {{ $daysLeft < 5 ? 'text-red-500 font-medium' : 'text-gray-600' }}">
                                                    {{ $daysLeft }} {{ Str::plural('day', $daysLeft) }} left
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-500">No subscription</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $purifier->next_service_date ? $purifier->next_service_date->format('jS M Y g:i A') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.purifiers.show', $purifier) }}"
                                class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                            <a href="{{ route('admin.purifiers.edit', $purifier) }}"
                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>

        <div class="mt-4" id="purifierPagination">
            {{ $purifiers->links() }}
        </div>
    </div>
</div>

<script>
    const purifierSearchInput = document.getElementById('purifierSearchInput');
    const purifierTableBody = document.getElementById('purifierTableBody');
    const purifierPagination = document.getElementById('purifierPagination');
    let purifierSearchTimeout;
    let purifierWasSearching = false;

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function formatDate(dateString) {
        if (!dateString) {
            return '-';
        }
        const date = new Date(dateString);
        if (Number.isNaN(date.getTime())) {
            return '-';
        }

        const day = date.getDate();
        const suffix = (day % 10 === 1 && day !== 11) ? 'st'
            : (day % 10 === 2 && day !== 12) ? 'nd'
            : (day % 10 === 3 && day !== 13) ? 'rd'
            : 'th';
        const month = date.toLocaleString('en-US', { month: 'short' });
        const year = date.getFullYear();
        const time = date.toLocaleString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true,
        });

        return `${day}${suffix} ${month} ${year} ${time}`;
    }

    function badgeForPurifierType(type) {
        const isRo = type === 'ro';
        return `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${isRo ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'}">${escapeHtml((type || '').charAt(0).toUpperCase() + (type || '').slice(1))}</span>`;
    }

    function badgeForPurifierStatus(status) {
        const map = {
            available: 'bg-green-100 text-green-800',
            assigned: 'bg-blue-100 text-blue-800',
            maintenance: 'bg-yellow-100 text-yellow-800',
            retired: 'bg-gray-100 text-gray-800',
        };
        const classes = map[status] || 'bg-gray-100 text-gray-800';
        const label = status ? status.charAt(0).toUpperCase() + status.slice(1) : 'Unknown';
        return `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${classes}">${escapeHtml(label)}</span>`;
    }

    function resolveDisplaySubscription(purifier) {
        const ownSubs = purifier.subscriptions || [];
        const customerSubs = purifier.customer?.subscriptions || [];
        const list = ownSubs.length ? ownSubs : customerSubs;
        if (!list.length) return null;

        const active = list
            .filter(subscription => subscription.status === 'active')
            .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

        if (active.length) {
            return active[0];
        }

        return [...list].sort((a, b) => new Date(b.created_at) - new Date(a.created_at))[0];
    }

    function renderSubscriptionCell(purifier) {
        const sub = resolveDisplaySubscription(purifier);
        if (!sub) {
            return '<span class="text-gray-500">No subscription</span>';
        }

        const statusClass = sub.status === 'active'
            ? 'bg-green-100 text-green-800'
            : (sub.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800');

        let progressHtml = '';
        if (sub.end_date) {
            const now = new Date();
            const startDate = sub.start_date ? new Date(sub.start_date) : null;
            const endDate = new Date(sub.end_date);
            const daysLeftRaw = (endDate - now) / (1000 * 60 * 60 * 24);
            const daysLeft = Math.max(0, Math.ceil(daysLeftRaw));
            const totalDays = startDate ? Math.max(0, (endDate - startDate) / (1000 * 60 * 60 * 24)) : 0;
            const percentage = totalDays > 0 ? Math.max(0, Math.min(100, (daysLeftRaw / totalDays) * 100)) : 0;
            const barClass = percentage > 20 ? 'bg-indigo-600' : 'bg-red-500';
            const dayClass = daysLeft < 5 ? 'text-red-500 font-medium' : 'text-gray-600';
            const dayText = `${daysLeft} day${daysLeft === 1 ? '' : 's'} left`;

            progressHtml = `
                <div class="mt-1">
                    <div class="flex items-center">
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full ${barClass} rounded-full" style="width: ${percentage}%"></div>
                        </div>
                        <span class="ml-2 text-xs ${dayClass}">${dayText}</span>
                    </div>
                </div>
            `;
        }

        return `
            <div class="flex flex-col space-y-1">
                <span class="text-gray-900">${escapeHtml(sub.plan?.name || 'Plan')}</span>
                <span class="px-2 inline-flex w-fit text-xs leading-5 font-semibold rounded-full ${statusClass}">${escapeHtml((sub.status || '').charAt(0).toUpperCase() + (sub.status || '').slice(1))}</span>
                ${progressHtml}
            </div>
        `;
    }

    function renderPurifierRow(purifier) {
        const customer = purifier.customer;
        const customerHtml = customer
            ? `<div class="flex flex-col"><span>${escapeHtml(customer.name || '')}</span><span class="text-xs text-gray-500">${escapeHtml(customer.phone || '')}</span></div>`
            : 'Not Assigned';

        return `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${escapeHtml(purifier.serial_number || '')}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${escapeHtml(purifier.model || '')}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">${badgeForPurifierType(purifier.type)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">${badgeForPurifierStatus(purifier.status)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${customerHtml}</td>
                <td class="px-6 py-4 text-sm">${renderSubscriptionCell(purifier)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${escapeHtml(formatDate(purifier.next_service_date))}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="/admin/purifiers/${purifier.id}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                    <a href="/admin/purifiers/${purifier.id}/edit" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                </td>
            </tr>
        `;
    }

    async function runPurifierSearch() {
        const query = purifierSearchInput.value.trim();

        if (query.length < 2) {
            if (purifierWasSearching) {
                window.location.reload();
            }
            return;
        }

        purifierWasSearching = true;

        try {
            const response = await fetch(`{{ route('admin.purifiers.search') }}?q=${encodeURIComponent(query)}`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            });

            const data = await response.json();
            const purifiers = data.purifiers || [];

            if (!purifiers.length) {
                purifierTableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">No purifiers found.</td></tr>';
            } else {
                purifierTableBody.innerHTML = purifiers.map(renderPurifierRow).join('');
            }

            purifierPagination.style.display = 'none';
        } catch (error) {
            console.error('Error searching purifiers:', error);
        }
    }

    purifierSearchInput.addEventListener('input', function () {
        clearTimeout(purifierSearchTimeout);
        purifierSearchTimeout = setTimeout(runPurifierSearch, 300);
    });
</script>
@endsection