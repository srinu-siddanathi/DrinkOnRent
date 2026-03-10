<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function loginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function dashboard()
    {
        $totalCustomers = Customer::count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $inactiveSubscriptions = Subscription::where('status', '!=', 'active')->count();
        $latestServicesQuery = $this->latestServicesPerCustomerQuery();
        $servicesSoonCount = (clone $latestServicesQuery)
            ->whereDate('services.expiry_date', '>=', today())
            ->whereDate('services.expiry_date', '<=', today()->copy()->addDays(7))
            ->count();
        $servicesDelayCount = (clone $latestServicesQuery)
            ->whereDate('services.expiry_date', '<', today())
            ->count();
        $totalRevenue = $this->calculateRevenue(now()->subDays(30), now());
        $todayRevenue = Subscription::join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->where('subscriptions.payment_status', 'completed')
            ->whereDate('subscriptions.created_at', today())
            ->sum('plans.price');
        
        return view('admin.dashboard', compact(
            'totalCustomers',
            'activeSubscriptions',
            'inactiveSubscriptions',
            'servicesSoonCount',
            'servicesDelayCount',
            'totalRevenue',
            'todayRevenue'
        ));
    }

    public function servicesSoon()
    {
        $services = $this->latestServicesPerCustomerQuery()
            ->whereDate('services.expiry_date', '>=', today())
            ->whereDate('services.expiry_date', '<=', today()->copy()->addDays(7))
            ->paginate(15);

        return view('admin.dashboard.service-list', [
            'title' => 'Service Soon',
            'subtitle' => 'Customers with services expiring within 7 days',
            'services' => $services,
            'mode' => 'soon',
        ]);
    }

    public function servicesDelay()
    {
        $services = $this->latestServicesPerCustomerQuery()
            ->whereDate('services.expiry_date', '<', today())
            ->paginate(15);

        return view('admin.dashboard.service-list', [
            'title' => 'Service Delay',
            'subtitle' => 'Customers with expired service dates',
            'services' => $services,
            'mode' => 'delay',
        ]);
    }

    public function revenue(Request $request)
    {
        $validated = $request->validate([
            'range' => 'nullable|in:7d,30d,3m,custom',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $range = $validated['range'] ?? '30d';
        $startDate = null;
        $endDate = now();

        if ($range === '7d') {
            $startDate = now()->subDays(7);
        } elseif ($range === '3m') {
            $startDate = now()->subMonths(3);
        } elseif ($range === 'custom') {
            if (empty($validated['start_date']) || empty($validated['end_date'])) {
                return response()->json([
                    'message' => 'start_date and end_date are required for custom range.'
                ], 422);
            }

            $startDate = Carbon::parse($validated['start_date'])->startOfDay();
            $endDate = Carbon::parse($validated['end_date'])->endOfDay();
        } else {
            $range = '30d';
            $startDate = now()->subDays(30);
        }

        $revenue = $this->calculateRevenue($startDate, $endDate);

        return response()->json([
            'range' => $range,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'revenue' => round($revenue, 2),
            'formatted_revenue' => number_format($revenue, 2),
        ]);
    }

    private function calculateRevenue($startDate, $endDate): float
    {
        return (float) Subscription::join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->where('subscriptions.payment_status', 'completed')
            ->whereBetween('subscriptions.created_at', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay(),
            ])
            ->sum('plans.price');
    }

    private function latestServicesPerCustomerQuery()
    {
        $latestServiceSubQuery = Service::query()
            ->select('customer_id')
            ->selectRaw('MAX(service_date) as latest_service_date')
            ->groupBy('customer_id');

        return Service::query()
            ->select('services.*')
            ->joinSub($latestServiceSubQuery, 'latest_services', function ($join) {
                $join->on('services.customer_id', '=', 'latest_services.customer_id')
                    ->on('services.service_date', '=', 'latest_services.latest_service_date');
            })
            ->with('customer')
            ->orderBy('services.expiry_date');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
} 