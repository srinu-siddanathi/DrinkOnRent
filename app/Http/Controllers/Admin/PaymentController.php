<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Subscription::with(['customer', 'plan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Subscription $payment)
    {
        $payment->load(['customer', 'plan', 'purifier']);
        return view('admin.payments.show', compact('payment'));
    }
} 