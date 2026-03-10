@extends('admin.layouts.app')

@section('title', 'Support Requests')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900">Support Requests</h2>

            <div class="mt-4 mb-6 flex flex-col md:flex-row gap-4">
                <div class="relative w-full md:w-[70%]">
                    <input 
                        type="text" 
                        id="supportSearchInput" 
                        placeholder="Search by name, email, or mobile..." 
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    />
                    <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <select id="supportStatusFilter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm w-full md:w-[30%]">
                    <option value="">All Statuses</option>
                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            
            <div class="mt-4 overflow-x-auto -mx-4 sm:mx-0">
                <div class="inline-block min-w-full">
                <table class="w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mobile</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="supportTableBody">
                        @foreach($requests as $request)
                        <tr class="cursor-pointer hover:bg-gray-50" onclick="window.location.href='{{ route('admin.support-requests.show', $request) }}'">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($request->customer)->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($request->customer)->phone ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->subject }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $request->status === 'resolved' ? 'bg-green-100 text-green-800' : 
                                       ($request->status === 'open' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.support-requests.show', $request) }}" onclick="event.stopPropagation()" class="text-indigo-600 hover:text-indigo-900">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>

            <div class="mt-4" id="supportPagination">
                {{ $requests->links() }}
            </div>
        </div>
    </div>

<script>
    const supportSearchInput = document.getElementById('supportSearchInput');
    const supportStatusFilter = document.getElementById('supportStatusFilter');
    const supportTableBody = document.getElementById('supportTableBody');
    const supportPagination = document.getElementById('supportPagination');
    let supportSearchTimeout;
    let supportWasFiltering = false;

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function statusBadgeClasses(status) {
        if (status === 'resolved') return 'bg-green-100 text-green-800';
        if (status === 'open') return 'bg-red-100 text-red-800';
        return 'bg-yellow-100 text-yellow-800';
    }

    function statusText(status) {
        return String(status || '').replace('_', ' ').replace(/\b\w/g, c => c.toUpperCase());
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        if (Number.isNaN(date.getTime())) return '-';
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        const hh = String(date.getHours()).padStart(2, '0');
        const mm = String(date.getMinutes()).padStart(2, '0');
        return `${y}-${m}-${d} ${hh}:${mm}`;
    }

    function renderSupportRow(item) {
        const customerName = escapeHtml(item.customer?.name || 'N/A');
        const mobile = escapeHtml(item.customer?.phone || 'N/A');
        const subject = escapeHtml(item.subject || '-');
        const created = formatDate(item.created_at);
        const status = item.status || 'open';

        return `
            <tr class="cursor-pointer hover:bg-gray-50" onclick="window.location.href='/admin/support-requests/${item.id}'">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${customerName}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${mobile}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${subject}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusBadgeClasses(status)}">
                        ${statusText(status)}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${created}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="/admin/support-requests/${item.id}" onclick="event.stopPropagation()" class="text-indigo-600 hover:text-indigo-900">View</a>
                </td>
            </tr>
        `;
    }

    async function runSupportFilter() {
        const query = supportSearchInput.value.trim();
        const status = supportStatusFilter.value;

        if (query.length < 2 && !status) {
            if (supportWasFiltering) {
                window.location.reload();
            }
            return;
        }

        supportWasFiltering = true;

        const params = new URLSearchParams();
        if (query.length >= 2) params.set('search', query);
        if (status) params.set('status', status);

        try {
            const response = await fetch(`{{ route('admin.support-requests.search') }}?${params.toString()}`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            });
            const data = await response.json();
            const requests = data.requests || [];

            if (!requests.length) {
                supportTableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">No support requests found.</td></tr>';
            } else {
                supportTableBody.innerHTML = requests.map(renderSupportRow).join('');
            }

            supportPagination.style.display = 'none';
        } catch (error) {
            console.error('Error filtering support requests:', error);
        }
    }

    supportSearchInput.addEventListener('input', function () {
        clearTimeout(supportSearchTimeout);
        supportSearchTimeout = setTimeout(runSupportFilter, 300);
    });

    supportStatusFilter.addEventListener('change', runSupportFilter);
</script>
@endsection 