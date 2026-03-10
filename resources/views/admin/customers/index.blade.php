@extends('admin.layouts.app')

@section('title', 'Customers')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-medium text-gray-900">Customers</h2>
                <a href="{{ route('admin.customers.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Register New Customer
                </a>
            </div>
            
            <!-- Search Bar -->
            <div class="mb-6">
                <div class="relative">
                    <input 
                        type="text" 
                        id="searchInput" 
                        placeholder="Search by name, phone, or area..." 
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
                            <th class="px-3 sm:px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-3 sm:px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-3 sm:px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Area</th>
                            <th class="px-3 sm:px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purifiers</th>
                            <th class="px-3 sm:px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active Plan</th>
                            <th class="px-3 sm:px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="customerTableBody">
                        @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('admin.customers.show', $customer) }}'">
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->first_name }}</td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->phone }}</td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->area }}</td>
                            
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($customer->purifiers->count() > 0)
                                    <div class="flex flex-col space-y-2">
                                        @foreach($customer->purifiers as $purifier)
                                            <div class="space-y-1">
                                                <span class="inline-flex items-center">
                                                    <span class="w-2 h-2 mr-2 rounded-full 
                                                        {{ $purifier->type === 'ro' ? 'bg-purple-400' : 'bg-blue-400' }}">
                                                    </span>
                                                    <span>{{ $purifier->serial_number }}</span>
                                                    <span class="ml-1 text-gray-500">({{ ucfirst($purifier->type) }})</span>
                                                </span>
                                                @if($purifier->latitude && $purifier->longitude)
                                                    <div class="text-xs text-gray-500 pl-4">
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            </svg>
                                                            <a href="https://www.google.com/maps?q={{ $purifier->latitude }},{{ $purifier->longitude }}" 
                                                               target="_blank"
                                                               onclick="event.stopPropagation();"
                                                               class="text-indigo-600 hover:text-indigo-900">
                                                                View Location
                                                            </a>
                                                        </div>
                                                        @if($purifier->location_address)
                                                            <div class="mt-1 pl-5">{{ $purifier->location_address }}</div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="text-xs text-gray-500 pl-4">Location not set</div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm">
                                @php
                                    $activeSubscriptions = $customer->subscriptions
                                        ->where('status', 'active')
                                        ->where('end_date', '>', now());
                                @endphp
                                
                                @if($activeSubscriptions->isNotEmpty())
                                    <div class="flex flex-col space-y-1">
                                        @foreach($activeSubscriptions as $subscription)
                                            <div class="flex items-center">
                                                <span class="w-2 h-2 mr-2 rounded-full 
                                                    {{ $subscription->plan->purifier_type === 'ro' ? 'bg-purple-400' : 'bg-blue-400' }}">
                                                </span>
                                                <span>{{ $subscription->plan->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500">No active plan</span>
                                @endif
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation();">
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="text-indigo-600 hover:text-indigo-900" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.customers.edit', $customer) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                    @if($customer->id_proof)
                                        <a href="{{ route('admin.customers.download-id-proof', $customer) }}" class="text-green-600 hover:text-green-900" title="Download ID Proof">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                No customers found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>

            <div class="mt-4" id="paginationDiv">
                {{ $customers->links() }}
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const customerTableBody = document.getElementById('customerTableBody');
        const paginationDiv = document.getElementById('paginationDiv');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let searchTimeout;
        let wasSearching = false;

        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                if (wasSearching) {
                    location.reload();
                    wasSearching = false;
                }
                return;
            }

            wasSearching = true;
            searchTimeout = setTimeout(() => {
                fetch('{{ route("admin.customers.search") }}?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        const customers = data.customers;
                        
                        if (customers.length === 0) {
                            customerTableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No customers found.</td></tr>';
                        } else {
                            customerTableBody.innerHTML = customers.map(customer => renderCustomerRow(customer)).join('');
                        }
                        
                        paginationDiv.style.display = 'none';
                    });
            }, 300);
        });

        function renderCustomerRow(customer) {
            const activeSubscriptions = customer.subscriptions.filter(s => s.status === 'active' && new Date(s.end_date) > new Date());
            const activePlanHTML = activeSubscriptions.length > 0 
                ? activeSubscriptions.map(s => `<div class="flex items-center"><span class="w-2 h-2 mr-2 rounded-full ${s.plan.purifier_type === 'ro' ? 'bg-purple-400' : 'bg-blue-400'}"></span><span>${s.plan.name}</span></div>`).join('')
                : '<span class="text-gray-500">No active plan</span>';

            const purifiersHTML = customer.purifiers && customer.purifiers.length > 0
                ? customer.purifiers.map(p => `<div class="space-y-1"><span class="inline-flex items-center"><span class="w-2 h-2 mr-2 rounded-full ${p.type === 'ro' ? 'bg-purple-400' : 'bg-blue-400'}"></span><span>${p.serial_number}</span><span class="ml-1 text-gray-500">(${p.type.toUpperCase()})</span></span></div>`).join('')
                : '<span class="text-gray-500">-</span>';

            return `
                <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='/admin/customers/${customer.id}'">
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">${customer.first_name}</td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">${customer.phone}</td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">${customer.area || ''}</td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex flex-col space-y-2">${purifiersHTML}</div>
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex flex-col space-y-1">${activePlanHTML}</div>
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation();">
                        <div class="flex space-x-3">
                            <a href="/admin/customers/${customer.id}" class="text-indigo-600 hover:text-indigo-900" title="View">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <a href="/admin/customers/${customer.id}/edit" class="text-blue-600 hover:text-blue-900" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="/admin/customers/${customer.id}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                            ${customer.id_proof ? `<a href="/admin/customers/${customer.id}/download-id-proof" class="text-green-600 hover:text-green-900" title="Download ID Proof"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg></a>` : ''}
                        </div>
                    </td>
                </tr>
            `;
        }
    </script>
@endsection 