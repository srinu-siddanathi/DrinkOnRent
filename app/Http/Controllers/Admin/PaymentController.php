<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'range' => 'nullable|in:today,7d,30d,3m,custom',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = Subscription::with(['customer', 'plan', 'paymentRecord'])->orderBy('created_at', 'desc');
        $range = $validated['range'] ?? null;

        if ($range) {
            $startDate = null;
            $endDate = now();

            if ($range === 'today') {
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
            } elseif ($range === '7d') {
                $startDate = now()->subDays(7)->startOfDay();
            } elseif ($range === '3m') {
                $startDate = now()->subMonths(3)->startOfDay();
            } elseif ($range === 'custom') {
                if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
                    $startDate = Carbon::parse($validated['start_date'])->startOfDay();
                    $endDate = Carbon::parse($validated['end_date'])->endOfDay();
                }
            } else {
                $range = '30d';
                $startDate = now()->subDays(30)->startOfDay();
            }

            if ($startDate) {
                $query->where('payment_status', 'completed')
                    ->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        $payments = $query->paginate(10)->withQueryString();

        return view('admin.payments.index', [
            'payments' => $payments,
            'range' => $range,
            'startDate' => $validated['start_date'] ?? null,
            'endDate' => $validated['end_date'] ?? null,
        ]);
    }

    public function show(Subscription $payment)
    {
        $payment->load(['customer', 'plan', 'purifier', 'paymentRecord']);

        $paymentHistory = Subscription::with(['plan', 'paymentRecord'])
            ->where('customer_id', $payment->customer_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.payments.show', compact('payment', 'paymentHistory'));
    }
} 