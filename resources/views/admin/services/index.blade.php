@extends('admin.layouts.app')

@section('title', 'New Services')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-lg font-medium text-gray-900">Services</h2>

            <form action="{{ route('admin.services.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                <input type="text" name="search" placeholder="Search by name or phone" value="{{ request('search') }}"
                       class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">

                <select name="area" onchange="this.form.submit()"
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Areas</option>
                    @foreach($areas as $area)
                        <option value="{{ $area }}" {{ request('area') == $area ? 'selected' : '' }}>{{ $area }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Area</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Date</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expire Date</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($customers as $customer)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->phone }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->area }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 max-w-xs truncate" title="{{ $customer->address }}">{{ Str::limit($customer->address, 30) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $customer->latestService ? $customer->latestService->next_service_reminder . 'M' : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($customer->purifiers->isNotEmpty())
                                {{ $customer->purifiers->first()->model ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $customer->latestService ? $customer->latestService->service_date->format('d-m-Y') : 'No service' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                            {{ $customer->latestService ? $customer->latestService->expiry_date->format('d-m-Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                             <button type="button" onclick="openHistoryModal({{ $customer->id }}, '{{ addslashes($customer->name) }}')" class="text-indigo-600 hover:text-indigo-900 mr-2">History</button>
                             <button type="button" onclick="openAddServiceModal({{ $customer->id }})" class="text-green-600 hover:text-green-900">Add Service</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $customers->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- History Modal -->
<div id="historyModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-xl font-medium text-gray-900" id="historyModalTitle">Service History</h3>
            <button onclick="closeModals()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="mt-4" id="historyModalContent">
            <p class="text-center text-gray-500">Loading...</p>
        </div>
    </div>
</div>

<!-- Add Service Modal -->
<div id="addServiceModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-xl font-medium text-gray-900">Add New Service</h3>
            <button onclick="closeModals()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
            @csrf
            <input type="hidden" name="customer_id" id="serviceCustomerId">

            <!-- Spare Parts -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Spare Parts</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @php
                        $parts = ['Sediment', 'Spun', 'Post/Carbon', 'Membrane Housing', 'Pump', 'Float', 'Pipe', 'Carbon', 'Tap', 'Membrane', 'SV', 'SMPS', 'Diveter Wall'];
                    @endphp
                    @foreach($parts as $part)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="spare_parts[]" value="{{ $part }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">{{ $part }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Service Date & Reminder -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Service Date</label>
                    <input type="date" name="service_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Next Service Reminder</label>
                    <div class="mt-2 space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="next_service_reminder" value="3" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">3 Months</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="next_service_reminder" value="6" checked class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">6 Months</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="next_service_reminder" value="12" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">12 Months</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Upload Images -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Upload Images</label>
                <input type="file" name="images[]" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <!-- Billing -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Total Bill</label>
                    <input type="number" name="total_amount" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Payment Mode</label>
                    <div class="mt-2 space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="payment_mode" value="UPI" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">UPI</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="payment_mode" value="Cash" checked class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Cash</span>
                        </label>
                         <label class="inline-flex items-center">
                            <input type="radio" name="payment_mode" value="Card" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Card</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-5 sm:mt-6">
                <button type="submit" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                    Save Service
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function closeModals() {
        document.getElementById('historyModal').classList.add('hidden');
        document.getElementById('addServiceModal').classList.add('hidden');
    }

    function openAddServiceModal(customerId) {
        document.getElementById('serviceCustomerId').value = customerId;
        document.getElementById('addServiceModal').classList.remove('hidden');
    }

    function openHistoryModal(customerId, customerName) {
        document.getElementById('historyModalTitle').innerText = 'Service History - ' + customerName;
        document.getElementById('historyModal').classList.remove('hidden');
        document.getElementById('historyModalContent').innerHTML = '<p class="text-center text-gray-500">Loading...</p>';

        fetch('/admin/customers/' + customerId + '/service-history')
            .then(response => response.json())
            .then(data => {
                document.getElementById('historyModalContent').innerHTML = data.html;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('historyModalContent').innerHTML = '<p class="text-center text-red-500">Failed to load history</p>';
            });
    }
</script>
@endsection
