@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="-m-2 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 items-start" style="row-gap: 1.25rem; column-gap: 1.25rem;">
        <a href="{{ route('admin.customers.index') }}" class="m-2 block bg-indigo-100 overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow min-h-[130px]">
            <div class="px-4 py-5 sm:p-6 flex items-center justify-between">
                <div>
                    <dt class="text-sm font-medium text-gray-600 truncate">
                        Total Customers
                    </dt>
                    <dd class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $totalCustomers }}
                    </dd>
                </div>
                <svg class="w-12 h-12 text-indigo-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </a>

        <a href="{{ route('admin.orders.index', ['status' => 'active']) }}" class="m-2 block bg-green-100 overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow min-h-[130px]">
            <div class="px-4 py-5 sm:p-6 flex items-center justify-between">
                <div>
                    <dt class="text-sm font-medium text-gray-600 truncate">
                        Active Subscriptions
                    </dt>
                    <dd class="mt-2 text-3xl font-bold text-green-600">
                        {{ $activeSubscriptions }}
                    </dd>
                </div>
                <svg class="w-12 h-12 text-green-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </a>

        <a href="{{ route('admin.orders.index', ['status' => 'inactive']) }}" class="m-2 block bg-rose-100 overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow min-h-[130px]">
            <div class="px-4 py-5 sm:p-6 flex items-center justify-between">
                <div>
                    <dt class="text-sm font-medium text-gray-600 truncate">
                        Inactive Subscriptions
                    </dt>
                    <dd class="mt-2 text-3xl font-bold text-rose-700">
                        {{ $inactiveSubscriptions }}
                    </dd>
                </div>
                <svg class="w-12 h-12 text-rose-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </a>

        <a href="{{ route('admin.payments.index', ['range' => 'today']) }}" class="m-2 block bg-yellow-100 overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow min-h-[130px]">
            <div class="px-4 py-5 sm:p-6 flex items-center justify-between">
                <div>
                    <dt class="text-sm font-medium text-gray-600 truncate">
                        Today's Revenue
                    </dt>
                    <dd class="mt-2 text-3xl font-bold text-yellow-600">
                        ₹{{ number_format($todayRevenue, 2) }}
                    </dd>
                </div>
                <svg class="w-12 h-12 text-yellow-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </a>

        <a href="{{ route('admin.dashboard.services-soon') }}" class="m-2 block bg-blue-100 overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow min-h-[130px]">
            <div class="px-4 py-5 sm:p-6 flex items-center justify-between">
                <div>
                    <dt class="text-sm font-medium text-gray-600 truncate">
                        Services Soon
                    </dt>
                    <dd class="mt-2 text-3xl font-bold text-blue-700">
                        {{ $servicesSoonCount }}
                    </dd>
                </div>
                <svg class="w-12 h-12 text-blue-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </a>

        <a href="{{ route('admin.dashboard.services-delay') }}" class="m-2 block bg-red-100 overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow min-h-[130px]">
            <div class="px-4 py-5 sm:p-6 flex items-center justify-between">
                <div>
                    <dt class="text-sm font-medium text-gray-600 truncate">
                        Services Delay
                    </dt>
                    <dd class="mt-2 text-3xl font-bold text-red-700">
                        {{ $servicesDelayCount }}
                    </dd>
                </div>
                <svg class="w-12 h-12 text-red-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M12 3a9 9 0 100 18 9 9 0 000-18z"/>
                </svg>
            </div>
        </a>

        <div class="m-2 bg-purple-100 overflow-hidden shadow rounded-lg sm:col-span-2 xl:col-span-2">
            <div class="px-4 py-5 sm:p-6">
                <a id="dashboardRevenueLink" href="{{ route('admin.payments.index', ['range' => '30d']) }}" class="group flex items-center justify-between gap-4 hover:opacity-90 transition-opacity">
                    <div>
                        <dt class="text-sm font-medium text-gray-600 truncate">
                            Total Revenue
                        </dt>
                        <dd id="dashboardRevenueValue" class="mt-2 text-3xl font-bold text-gray-900">
                            ₹{{ number_format($totalRevenue, 2) }}
                        </dd>
                    </div>
                    <svg class="w-12 h-12 text-purple-400 opacity-50 group-hover:opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </a>

                <div class="mt-4 rounded-md border border-purple-200 bg-white/70 p-3 space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2 items-end">
                        <div class="md:col-span-1">
                            <label for="revenueRange" class="block text-xs font-semibold text-gray-700 mb-1">Revenue Range</label>
                            <select id="revenueRange" class="w-full h-10 px-3 text-sm border border-purple-300 rounded-md bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="7d">Last 7 Days</option>
                            <option value="30d" selected>Last 30 Days</option>
                            <option value="3m">Last 3 Months</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>

                        <div id="customDateWrapper" class="hidden md:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-2 items-end">
                            <div>
                                <label for="revenueStartDate" class="block text-xs font-semibold text-gray-700 mb-1">Start Date</label>
                                <input id="revenueStartDate" type="date" class="w-full h-10 px-3 text-sm border border-purple-300 rounded-md bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500" />
                            </div>
                            <div>
                                <label for="revenueEndDate" class="block text-xs font-semibold text-gray-700 mb-1">End Date</label>
                                <input id="revenueEndDate" type="date" class="w-full h-10 px-3 text-sm border border-purple-300 rounded-md bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500" />
                            </div>
                            <button id="applyCustomRevenue" type="button" class="h-10 px-3 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 transition-colors">Apply</button>
                        </div>
                    </div>

                    <p id="revenueRangeLabel" class="text-xs text-gray-600">Showing last 30 days revenue</p>
                    <p id="revenueError" class="text-xs text-red-600 hidden"></p>
                </div>
            </div>
        </div>
    </div>
@endsection 

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const revenueValue = document.getElementById('dashboardRevenueValue');
    const rangeSelect = document.getElementById('revenueRange');
    const customWrapper = document.getElementById('customDateWrapper');
    const startDateInput = document.getElementById('revenueStartDate');
    const endDateInput = document.getElementById('revenueEndDate');
    const applyButton = document.getElementById('applyCustomRevenue');
    const rangeLabel = document.getElementById('revenueRangeLabel');
    const errorLabel = document.getElementById('revenueError');
    const revenueLink = document.getElementById('dashboardRevenueLink');

    const setLoading = (isLoading) => {
        if (isLoading) {
            revenueValue.dataset.previous = revenueValue.textContent;
            revenueValue.textContent = 'Loading...';
        } else if (revenueValue.dataset.previous) {
            delete revenueValue.dataset.previous;
        }
    };

    const setError = (message) => {
        if (message) {
            errorLabel.textContent = message;
            errorLabel.classList.remove('hidden');
        } else {
            errorLabel.textContent = '';
            errorLabel.classList.add('hidden');
        }
    };

    const updateLabel = (range, startDate, endDate) => {
        if (range === '7d') {
            rangeLabel.textContent = 'Showing last 7 days revenue';
            revenueLink.href = `{{ route('admin.payments.index') }}?range=7d`;
            return;
        }

        if (range === '3m') {
            rangeLabel.textContent = 'Showing last 3 months revenue';
            revenueLink.href = `{{ route('admin.payments.index') }}?range=3m`;
            return;
        }

        if (range === 'custom') {
            rangeLabel.textContent = `Showing revenue from ${startDate} to ${endDate}`;
            const params = new URLSearchParams({
                range: 'custom',
                start_date: startDate,
                end_date: endDate,
            });
            revenueLink.href = `{{ route('admin.payments.index') }}?${params.toString()}`;
            return;
        }

        rangeLabel.textContent = 'Showing last 30 days revenue';
        revenueLink.href = `{{ route('admin.payments.index') }}?range=30d`;
    };

    const fetchRevenue = async (params) => {
        setError('');
        setLoading(true);

        try {
            const query = new URLSearchParams(params);
            const response = await fetch(`{{ route('admin.dashboard.revenue') }}?${query.toString()}`, {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch revenue.');
            }

            revenueValue.textContent = `₹${data.formatted_revenue}`;
            updateLabel(data.range, data.start_date, data.end_date);
        } catch (error) {
            revenueValue.textContent = revenueValue.dataset.previous || revenueValue.textContent;
            setError(error.message || 'Failed to fetch revenue.');
        } finally {
            setLoading(false);
        }
    };

    rangeSelect.addEventListener('change', function () {
        const selectedRange = this.value;

        if (selectedRange === 'custom') {
            customWrapper.classList.remove('hidden');
            return;
        }

        customWrapper.classList.add('hidden');
        fetchRevenue({ range: selectedRange });
    });

    applyButton.addEventListener('click', function () {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        if (!startDate || !endDate) {
            setError('Please select both start and end dates for custom range.');
            return;
        }

        fetchRevenue({
            range: 'custom',
            start_date: startDate,
            end_date: endDate,
        });
    });

    const onCustomDateChange = () => {
        if (rangeSelect.value !== 'custom') {
            return;
        }

        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        if (startDate && endDate) {
            fetchRevenue({
                range: 'custom',
                start_date: startDate,
                end_date: endDate,
            });
        }
    };

    startDateInput.addEventListener('change', onCustomDateChange);
    endDateInput.addEventListener('change', onCustomDateChange);

    fetchRevenue({ range: '30d' });
});
</script>
@endpush