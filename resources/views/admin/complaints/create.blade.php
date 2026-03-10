@extends('admin.layouts.app')

@section('title', 'New Complaint')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-6">New Complaint</h3>

        <form action="{{ route('admin.complaints.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="customer_id" class="block text-sm font-medium text-gray-700">
                    Customer <span class="text-red-500">*</span>
                </label>
                <input type="hidden" name="customer_id" id="customer_id" value="{{ old('customer_id') }}" required>
                <div class="relative mt-1">
                    <input type="text" id="customer_search"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('customer_id') border-red-500 @enderror"
                           placeholder="Search by customer name or mobile number"
                           autocomplete="off">
                    <div id="customer_suggestions" class="hidden absolute z-20 mt-1 w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-56 overflow-y-auto"></div>
                </div>
                <p id="selected_customer_text" class="mt-1 text-sm text-gray-600 hidden"></p>
                @error('customer_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="details" class="block text-sm font-medium text-gray-700">
                    Complaint Details <span class="text-red-500">*</span>
                </label>
                <textarea name="details" id="details" rows="5"
                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('details') border-red-500 @enderror"
                          placeholder="Describe the complaint in detail"
                          required>{{ old('details') }}</textarea>
                @error('details')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.complaints.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Submit Complaint
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const customerSearchInput = document.getElementById('customer_search');
    const customerIdInput = document.getElementById('customer_id');
    const suggestionsBox = document.getElementById('customer_suggestions');
    const selectedCustomerText = document.getElementById('selected_customer_text');
    let debounceTimer;

    const hideSuggestions = () => {
        suggestionsBox.classList.add('hidden');
        suggestionsBox.innerHTML = '';
    };

    const setSelectedCustomer = (customer) => {
        customerIdInput.value = customer.id;
        customerSearchInput.value = `${customer.name} (${customer.phone})`;
        selectedCustomerText.textContent = `Selected: ${customer.name} - ${customer.phone}`;
        selectedCustomerText.classList.remove('hidden');
        hideSuggestions();
    };

    customerSearchInput.addEventListener('input', function () {
        const query = this.value.trim();
        customerIdInput.value = '';
        selectedCustomerText.classList.add('hidden');

        clearTimeout(debounceTimer);

        if (query.length < 2) {
            hideSuggestions();
            return;
        }

        debounceTimer = setTimeout(async () => {
            try {
                const response = await fetch(`{{ route('admin.customers.search') }}?q=${encodeURIComponent(query)}`, {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                });

                const data = await response.json();
                const customers = data.customers || [];

                if (!customers.length) {
                    suggestionsBox.innerHTML = '<div class="px-3 py-2 text-sm text-gray-500">No customers found</div>';
                    suggestionsBox.classList.remove('hidden');
                    return;
                }

                suggestionsBox.innerHTML = customers.map((customer) => `
                    <button type="button" class="w-full text-left px-3 py-2 text-sm hover:bg-indigo-50" data-id="${customer.id}" data-name="${customer.name}" data-phone="${customer.phone}">
                        <div class="font-medium text-gray-900">${customer.name}</div>
                        <div class="text-gray-500">${customer.phone}</div>
                    </button>
                `).join('');
                suggestionsBox.classList.remove('hidden');
            } catch (error) {
                hideSuggestions();
            }
        }, 250);
    });

    suggestionsBox.addEventListener('click', function (event) {
        const item = event.target.closest('button[data-id]');
        if (!item) {
            return;
        }

        setSelectedCustomer({
            id: item.dataset.id,
            name: item.dataset.name,
            phone: item.dataset.phone,
        });
    });

    document.addEventListener('click', function (event) {
        if (!suggestionsBox.contains(event.target) && event.target !== customerSearchInput) {
            hideSuggestions();
        }
    });
});
</script>
@endsection
