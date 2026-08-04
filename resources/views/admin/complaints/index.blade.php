@extends('admin.layouts.app')

@section('title', 'Complaints')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-lg font-medium text-gray-900">Complaints</h2>
            <a href="{{ route('admin.complaints.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                New Complaint
            </a>
        </div>

        <!-- Search -->
        <div class="mb-6">
            <form action="{{ route('admin.complaints.index') }}" method="GET" class="w-full" onsubmit="return false;">
                <div class="relative">
                    <input 
                        type="text" 
                        id="complaintsSearchInput"
                        name="search" 
                        placeholder="Search by customer name, mobile or details" 
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    />
                    <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </form>
        </div>

        <div class="mt-4 overflow-x-auto -mx-4 sm:mx-0">
            <div class="inline-block min-w-full">
            <table class="w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mobile</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="complaintsTableBody">
                    @forelse ($complaints as $complaint)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $complaint->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $complaint->customer->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $complaint->customer->phone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 max-w-xs truncate" title="{{ $complaint->details }}">{{ Str::limit($complaint->details, 30) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $complaint->created_at->format('jS M Y g:i A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <form action="{{ route('admin.complaints.update', $complaint->id) }}" method="POST" class="update-status-form">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm w-full" onchange="this.form.submit()">
                                        <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form action="{{ route('admin.complaints.destroy', $complaint->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this complaint?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">No complaints found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        <div class="mt-4" id="complaintsPagination">
            {{ $complaints->links() }}
        </div>
    </div>
</div>

<script>
    const complaintsSearchInput = document.getElementById('complaintsSearchInput');
    const complaintsTableBody = document.getElementById('complaintsTableBody');
    const complaintsPagination = document.getElementById('complaintsPagination');
    let complaintsSearchTimeout;
    let complaintsWasFiltering = false;

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function complaintStatusSelect(complaint) {
        const pendingSelected = complaint.status === 'pending' ? 'selected' : '';
        const resolvedSelected = complaint.status === 'resolved' ? 'selected' : '';

        return `
            <form action="/admin/complaints/${complaint.id}" method="POST" class="update-status-form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="PUT">
                <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm w-full" onchange="this.form.submit()">
                    <option value="pending" ${pendingSelected}>Pending</option>
                    <option value="resolved" ${resolvedSelected}>Resolved</option>
                </select>
            </form>
        `;
    }

    function complaintDeleteAction(complaintId) {
        return `
            <form action="/admin/complaints/${complaintId}" method="POST" onsubmit="return confirm('Are you sure you want to delete this complaint?');">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
            </form>
        `;
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        if (Number.isNaN(date.getTime())) return '-';

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

    function renderComplaintRow(complaint) {
        const customerName = escapeHtml(complaint.customer?.name || 'N/A');
        const mobile = escapeHtml(complaint.customer?.phone || '-');
        const detailsRaw = complaint.details || '';
        const detailsShort = detailsRaw.length > 30 ? `${detailsRaw.slice(0, 30)}...` : detailsRaw;

        return `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${complaint.id}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${customerName}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${mobile}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 max-w-xs truncate" title="${escapeHtml(detailsRaw)}">${escapeHtml(detailsShort)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatDate(complaint.created_at)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${complaintStatusSelect(complaint)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">${complaintDeleteAction(complaint.id)}</td>
            </tr>
        `;
    }

    async function runComplaintsSearch() {
        const search = complaintsSearchInput.value.trim();

        if (search.length < 2) {
            if (complaintsWasFiltering) {
                window.location.reload();
            }
            return;
        }

        complaintsWasFiltering = true;

        const params = new URLSearchParams();
        params.set('search', search);

        try {
            const response = await fetch(`{{ route('admin.complaints.search') }}?${params.toString()}`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            });

            const data = await response.json();
            const complaints = data.complaints || [];

            if (!complaints.length) {
                complaintsTableBody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">No complaints found.</td></tr>';
            } else {
                complaintsTableBody.innerHTML = complaints.map(renderComplaintRow).join('');
            }

            complaintsPagination.style.display = 'none';
        } catch (error) {
            console.error('Error filtering complaints:', error);
        }
    }

    complaintsSearchInput.addEventListener('input', function () {
        clearTimeout(complaintsSearchTimeout);
        complaintsSearchTimeout = setTimeout(runComplaintsSearch, 300);
    });
</script>
@endsection
