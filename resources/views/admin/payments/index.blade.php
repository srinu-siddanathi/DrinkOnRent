@extends('admin.layouts.app')

@section('title', 'Payments')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-gray-900">Payments</h2>
        </div>

        <div class="mb-6">
            <div class="relative">
                <input 
                    type="text" 
                    id="paymentSearchInput" 
                    placeholder="Search by customer name, mobile, or transaction ID..." 
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
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Mobile
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Plan
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Transaction ID
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="paymentsTableBody">
                                    @forelse($payments as $payment)
                                        <tr class="js-payment-row cursor-pointer hover:bg-gray-50" data-href="{{ route('admin.payments.show', $payment) }}" tabindex="0" role="link" aria-label="View payment {{ $payment->id }} details">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $payment->customer->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $payment->customer->email }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $payment->customer->phone ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $payment->plan->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $payment->plan->purifier_type }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">₹{{ number_format($payment->plan->price, 2) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $payment->payment_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ ucfirst($payment->payment_status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $payment->paymentRecord->razorpay_payment_id ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $payment->created_at->format('M d, Y h:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('admin.payments.show', $payment) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                      </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                No payments found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
            </div>
        </div>

        <div class="mt-4" id="paymentsPagination">
            {{ $payments->links() }}
        </div>
    </div>
</div>

<script>
    const paymentSearchInput = document.getElementById('paymentSearchInput');
    const paymentsTableBody = document.getElementById('paymentsTableBody');
    const paymentsPagination = document.getElementById('paymentsPagination');
    let paymentSearchTimeout;
    let paymentWasSearching = false;

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        if (Number.isNaN(date.getTime())) return '-';
        return date.toLocaleString('en-US', {
            month: 'short',
            day: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true,
        });
    }

    function paymentStatusBadge(status) {
        const isCompleted = status === 'completed';
        const classes = isCompleted ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
        const label = status ? status.charAt(0).toUpperCase() + status.slice(1) : '-';
        return `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${classes}">${escapeHtml(label)}</span>`;
    }

    function renderPaymentRow(payment) {
        const customer = payment.customer || {};
        const plan = payment.plan || {};
        const paymentRecord = payment.payment_record || {};
        const amount = plan.price ? Number(plan.price).toFixed(2) : '0.00';

        return `
            <tr class="js-payment-row cursor-pointer hover:bg-gray-50" data-href="/admin/payments/${payment.id}" tabindex="0" role="link" aria-label="View payment ${escapeHtml(payment.id)} details">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${escapeHtml(customer.name || 'N/A')}</div>
                    <div class="text-sm text-gray-500">${escapeHtml(customer.email || '-')}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${escapeHtml(customer.phone || '-')}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${escapeHtml(plan.name || '-')}</div>
                    <div class="text-sm text-gray-500">${escapeHtml(plan.purifier_type || '-')}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-900">₹${amount}</div></td>
                <td class="px-6 py-4 whitespace-nowrap">${paymentStatusBadge(payment.payment_status)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${escapeHtml(paymentRecord.razorpay_payment_id || '-')}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${escapeHtml(formatDate(payment.created_at))}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="/admin/payments/${payment.id}" class="text-indigo-600 hover:text-indigo-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                </td>
            </tr>
        `;
    }

    async function runPaymentSearch() {
        const query = paymentSearchInput.value.trim();

        if (query.length < 2) {
            if (paymentWasSearching) {
                window.location.reload();
            }
            return;
        }

        paymentWasSearching = true;

        const params = new URLSearchParams();
        params.set('q', query);

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('range')) params.set('range', urlParams.get('range'));
        if (urlParams.get('start_date')) params.set('start_date', urlParams.get('start_date'));
        if (urlParams.get('end_date')) params.set('end_date', urlParams.get('end_date'));

        try {
            const response = await fetch(`{{ route('admin.payments.search') }}?${params.toString()}`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });
            const data = await response.json();
            const payments = data.payments || [];

            if (!payments.length) {
                paymentsTableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No payments found.</td></tr>';
            } else {
                paymentsTableBody.innerHTML = payments.map(renderPaymentRow).join('');
            }

            paymentsPagination.style.display = 'none';
        } catch (error) {
            console.error('Error searching payments:', error);
        }
    }

    paymentSearchInput.addEventListener('input', function () {
        clearTimeout(paymentSearchTimeout);
        paymentSearchTimeout = setTimeout(runPaymentSearch, 300);
    });

    paymentsTableBody.addEventListener('click', function (event) {
        const interactiveElement = event.target.closest('a, button, input, select, textarea, label');
        if (interactiveElement) return;

        const row = event.target.closest('.js-payment-row');
        if (!row || !paymentsTableBody.contains(row)) return;

        const href = row.dataset.href;
        if (href) {
            window.location.href = href;
        }
    });

    paymentsTableBody.addEventListener('keydown', function (event) {
        if (event.key !== 'Enter' && event.key !== ' ') return;

        const row = event.target.closest('.js-payment-row');
        if (!row || !paymentsTableBody.contains(row)) return;

        event.preventDefault();
        const href = row.dataset.href;
        if (href) {
            window.location.href = href;
        }
    });
</script>
@endsection 