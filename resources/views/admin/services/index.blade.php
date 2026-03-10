@extends('admin.layouts.app')

@section('title', 'Services')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-lg font-medium text-gray-900">Services</h2>
        </div>

        <div class="mb-6">
            <form action="{{ route('admin.services.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                <input type="text" name="search" placeholder="Search by name or phone" value="{{ request('search') }}"
                       class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm w-full md:w-auto">

                <select name="area" onchange="this.form.submit()"
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm w-full md:w-auto">
                    <option value="">All Areas</option>
                    @foreach($areas as $area)
                        <option value="{{ $area }}" {{ request('area') == $area ? 'selected' : '' }}>{{ $area }}</option>
                    @endforeach
                </select>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Search
                </button>
            </form>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Area</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Service Date</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Next Service Date</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($customers as $customer)
                    <tr class="cursor-pointer hover:bg-gray-50" onclick="openHistoryModal({{ $customer->id }}, '{{ addslashes($customer->name) }}')">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="text-indigo-600 font-bold">{{ $customer->name }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->phone }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->area }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $customer->latestService ? $customer->latestService->next_service_reminder . 'M' : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($customer->purifiers->isNotEmpty())
                                {{ ucfirst($customer->purifiers->first()->type ?? '-') }}
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
                            <a href="#" onclick="event.stopPropagation(); openHistoryModal({{ $customer->id }}, '{{ addslashes($customer->name) }}'); return false;" class="text-indigo-600 hover:text-indigo-900">History</a>
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

<div id="historyModal" class="hidden fixed inset-0 z-50 transition-opacity duration-300 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.75);">
    <div class="relative p-0 border-0 w-11/12 md:w-10/12 max-w-7xl shadow-lg rounded-xl bg-gray-100 transform transition-all duration-300 scale-95 opacity-0 -translate-y-10 max-h-[90vh] flex flex-col">
        <div class="flex justify-between items-center px-6 py-4 bg-blue-800 rounded-t-xl gap-4">
            <h3 class="text-xl font-semibold text-white" id="historyModalTitle">Service History</h3>
            <div class="flex-shrink-0">
                <button id="addServiceFromHistory" class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Add Service
                </button>
                <button onclick="closeModals()" class="text-white hover:text-gray-200 transition-colors ml-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="p-6 overflow-y-auto" id="historyModalContent">
            <p class="text-center text-gray-500">Loading...</p>
        </div>
    </div>
</div>

<div id="addServiceModal" class="hidden fixed inset-0 z-50 transition-opacity duration-300 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.75);">
    <div class="relative p-0 border-0 w-11/12 md:w-3/4 lg:max-w-lg shadow-lg rounded-xl bg-gray-100 transform transition-all duration-300 scale-95 opacity-0 -translate-y-10 max-h-[90vh] flex flex-col">
        <div class="flex justify-between items-center px-6 py-4 bg-blue-800 rounded-t-xl flex-shrink-0">
            <h3 class="text-xl font-semibold text-white">Add New Service</h3>
            <button onclick="closeModals()" class="text-white hover:text-gray-200 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="overflow-y-auto">
            <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                <input type="hidden" name="customer_id" id="serviceCustomerId">

                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Spare Parts</h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @php
                            $parts = ['Sediment', 'Spun', 'Post/Carbon', 'Membrane Housing', 'Pump', 'Float', 'Pipe', 'Carbon', 'Tap', 'Membrane', 'SV', 'SMPS', 'Diveter Wall'];
                        @endphp
                        @foreach($parts as $part)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="spare_parts[]" value="{{ $part }}" class="rounded-sm border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">{{ $part }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Service Date & Reminder</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Service Date</label>
                            <input type="date" name="service_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Next Service Reminder</label>
                            <div class="mt-2 flex items-center space-x-6">
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
                </div>

                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Upload Images</h4>
                    <input type="file" name="images[]" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border file:border-gray-300 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                </div>

                <div class="mt-8 flex justify-end space-x-4 flex-shrink-0">
                    <button type="button" onclick="closeModals()" class="px-6 py-2 rounded-md text-gray-700 bg-gray-200 hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 rounded-md text-white bg-blue-800 hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Save Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="serviceImageModal" class="hidden fixed inset-0 flex items-center justify-center p-4" style="z-index: 9999; background-color: rgba(0,0,0,0.85);">
    <button type="button" onclick="closeServiceImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-200">
        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    <img id="serviceImageModalImg" src="" alt="Service Image" class="max-h-[85vh] max-w-[95vw] object-contain rounded-lg shadow-2xl" />
</div>

<script>
    const historyModal = document.getElementById('historyModal');
    const addServiceModal = document.getElementById('addServiceModal');
    const serviceCustomerId = document.getElementById('serviceCustomerId');

    let currentHistoryCustomerId = null;
    let currentHistoryCustomerName = '';
    let currentHistoryYear = '';
    const serviceImageModal = document.getElementById('serviceImageModal');
    const serviceImageModalImg = document.getElementById('serviceImageModalImg');

    function openServiceImageModal(imageUrl) {
        serviceImageModalImg.src = imageUrl;
        serviceImageModal.classList.remove('hidden');
    }

    function closeServiceImageModal() {
        serviceImageModal.classList.add('hidden');
        serviceImageModalImg.src = '';
    }

    function closeModals() {
        const historyModalContent = historyModal.querySelector('.transform');
        const addServiceModalContent = addServiceModal.querySelector('.transform');

        if (historyModalContent) {
            historyModalContent.classList.add('scale-95', 'opacity-0', '-translate-y-10');
        }
        if (addServiceModalContent) {
            addServiceModalContent.classList.add('scale-95', 'opacity-0', '-translate-y-10');
        }

        setTimeout(() => {
            historyModal.classList.add('hidden');
            addServiceModal.classList.add('hidden');
        }, 300);
    }

    function openAddServiceModal(customerId) {
        serviceCustomerId.value = customerId;
        addServiceModal.classList.remove('hidden');
        setTimeout(() => {
            const modalContent = addServiceModal.querySelector('.transform');
            modalContent.classList.remove('scale-95', 'opacity-0', '-translate-y-10');
        }, 10);
    }

    function applyHistoryYearFilter(year) {
        if (!currentHistoryCustomerId) {
            return;
        }
        currentHistoryYear = year || '';
        openHistoryModal(currentHistoryCustomerId, currentHistoryCustomerName, currentHistoryYear);
    }

    function openHistoryModal(customerId, customerName, year = '') {
        currentHistoryCustomerId = customerId;
        currentHistoryCustomerName = customerName;
        currentHistoryYear = year || '';

        document.getElementById('historyModalTitle').innerText = 'Service History - ' + customerName;
        document.getElementById('addServiceFromHistory').onclick = () => {
            closeModals();
            setTimeout(() => openAddServiceModal(customerId), 350);
        };

        historyModal.classList.remove('hidden');
        setTimeout(() => {
            const modalContent = historyModal.querySelector('.transform');
            modalContent.classList.remove('scale-95', 'opacity-0', '-translate-y-10');
        }, 10);

        document.getElementById('historyModalContent').innerHTML = '<p class="text-center text-gray-500">Loading...</p>';

        const params = new URLSearchParams();
        if (currentHistoryYear) {
            params.set('year', currentHistoryYear);
        }

        const url = '/admin/customers/' + customerId + '/service-history' + (params.toString() ? ('?' + params.toString()) : '');

        fetch(url, {
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (!data.html) {
                    throw new Error('Invalid response received from server.');
                }

                document.getElementById('historyModalContent').innerHTML = data.html;
            })
            .catch(error => {
                console.error('Error fetching service history:', error);
                document.getElementById('historyModalContent').innerHTML = '<p class="text-center text-red-500">Failed to load history. Please try again.</p>';
            });
    }

    function showServiceDetails(serviceId) {
        const list = document.getElementById('service-list');
        if (list) {
            list.classList.add('hidden');
        }

        const filter = document.getElementById('historyFilterWrapper');
        if (filter) {
            filter.classList.add('hidden');
        }

        const details = document.querySelectorAll('[id^="service-details-"]');
        details.forEach(detail => detail.classList.add('hidden'));

        const selected = document.getElementById('service-details-' + serviceId);
        if (selected) {
            selected.classList.remove('hidden');
        }
    }

    function showServiceList() {
        const list = document.getElementById('service-list');
        if (list) {
            list.classList.remove('hidden');
        }

        const filter = document.getElementById('historyFilterWrapper');
        if (filter) {
            filter.classList.remove('hidden');
        }

        const details = document.querySelectorAll('[id^="service-details-"]');
        details.forEach(detail => detail.classList.add('hidden'));
    }

    document.getElementById('historyModalContent').addEventListener('click', function (event) {
        const imageLink = event.target.closest('.service-gallery a');
        if (!imageLink) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();
        openServiceImageModal(imageLink.getAttribute('href'));
    });

    serviceImageModal.addEventListener('click', function (event) {
        if (event.target === serviceImageModal) {
            closeServiceImageModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            if (!serviceImageModal.classList.contains('hidden')) {
                closeServiceImageModal();
            } else {
                closeModals();
            }
        }
    });
</script>
@endsection
