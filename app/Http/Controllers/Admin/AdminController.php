<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

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

    public function passwordChangeForm()
    {
        return view('admin.auth.password-change');
    }

    public function passwordChange(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password:admin'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $admin = Auth::guard('admin')->user();

        if (Hash::check($validated['password'], $admin->password)) {
            return back()
                ->withErrors(['password' => 'Your new password must be different from your current password.'])
                ->withInput();
        }

        $admin->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    public function forgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendPasswordResetOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower(trim($validated['email']));
        $admin = \App\Models\Admin::where('email', $email)->first();

        if ($admin) {
            $otp = (string) random_int(100000, 999999);
            $cacheKey = 'admin-password-reset:' . hash('sha256', $email);

            Cache::put($cacheKey, [
                'otp_hash' => Hash::make($otp),
                'attempts' => 0,
            ], now()->addMinutes(10));

            $request->session()->put('admin_password_reset_email', $email);

            try {
                Mail::raw("Your DrinkOnRent admin password reset OTP is: {$otp}\n\nThis OTP expires in 10 minutes.", function ($message) use ($email) {
                    $message->to($email)->subject('DrinkOnRent Admin Password Reset OTP');
                });
            } catch (\Throwable $exception) {
                Log::error('Admin password reset OTP email failed', ['email' => $email, 'error' => $exception->getMessage()]);
            }
        }

        return redirect()->route('admin.password.reset.verify')
            ->with('success', 'If an admin account exists for that email, an OTP has been sent.');
    }

    public function passwordResetVerifyForm(Request $request)
    {
        if (!$request->session()->has('admin_password_reset_email')) {
            return redirect()->route('admin.password.forgot');
        }

        return view('admin.auth.verify-password-reset');
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'otp' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $email = $request->session()->get('admin_password_reset_email');
        if (!$email) {
            return redirect()->route('admin.password.forgot')->withErrors([
                'email' => 'Your password reset session has expired. Please start again.',
            ]);
        }

        $cacheKey = 'admin-password-reset:' . hash('sha256', $email);
        $reset = Cache::get($cacheKey);

        if (!$reset || ($reset['attempts'] ?? 0) >= 5) {
            Cache::forget($cacheKey);
            $request->session()->forget('admin_password_reset_email');
            return redirect()->route('admin.password.forgot')->withErrors([
                'email' => 'The OTP is invalid or has expired. Please request a new one.',
            ]);
        }

        if (!Hash::check($validated['otp'], $reset['otp_hash'])) {
            $reset['attempts'] = ($reset['attempts'] ?? 0) + 1;
            Cache::put($cacheKey, $reset, now()->addMinutes(10));

            return back()->withErrors(['otp' => 'The OTP is incorrect.']);
        }

        $admin = \App\Models\Admin::where('email', $email)->first();
        if (!$admin) {
            return redirect()->route('admin.password.forgot')->withErrors([
                'email' => 'The admin account could not be found.',
            ]);
        }

        $admin->update(['password' => Hash::make($validated['password'])]);
        Cache::forget($cacheKey);
        $request->session()->forget('admin_password_reset_email');

        return redirect()->route('admin.login')->with('success', 'Password reset successfully. Please sign in.');
    }

    public function resendPasswordResetOtp(Request $request)
    {
        $email = $request->session()->get('admin_password_reset_email');
        if (!$email) {
            return redirect()->route('admin.password.forgot');
        }

        $admin = \App\Models\Admin::where('email', $email)->first();
        if (!$admin) {
            $request->session()->forget('admin_password_reset_email');
            return redirect()->route('admin.password.forgot');
        }

        $otp = (string) random_int(100000, 999999);
        $cacheKey = 'admin-password-reset:' . hash('sha256', $email);
        Cache::put($cacheKey, [
            'otp_hash' => Hash::make($otp),
            'attempts' => 0,
        ], now()->addMinutes(10));

        try {
            Mail::raw("Your DrinkOnRent admin password reset OTP is: {$otp}\n\nThis OTP expires in 10 minutes.", function ($message) use ($email) {
                $message->to($email)->subject('DrinkOnRent Admin Password Reset OTP');
            });
        } catch (\Throwable $exception) {
            Log::error('Admin password reset OTP email failed', ['email' => $email, 'error' => $exception->getMessage()]);
        }

        return back()->with('success', 'A new OTP has been sent to your email.');
    }

    public function emailTestForm()
    {
        $admin = Auth::guard('admin')->user();

        return view('admin.settings.email-test', [
            'adminEmail' => $admin?->email,
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'scheme' => config('mail.mailers.smtp.scheme'),
            'fromAddress' => config('mail.from.address'),
            'fromName' => config('mail.from.name'),
        ]);
    }

    public function sendTestEmail(Request $request)
    {
        $validated = $request->validate(['email' => ['required', 'email']]);
        $recipient = strtolower(trim($validated['email']));
        $startedAt = microtime(true);

        try {
            Mail::raw(
                'This is a test email from the DrinkOnRent admin panel. If you received this message, Laravel SMTP delivery is working.',
                function ($message) use ($recipient) {
                    $message->to($recipient)->subject('DrinkOnRent SMTP Test');
                }
            );

            $duration = round((microtime(true) - $startedAt) * 1000);
            Log::info('Admin SMTP test email sent', [
                'recipient' => $recipient,
                'mailer' => config('mail.default'),
                'duration_ms' => $duration,
            ]);

            return back()->with('success', "Test email accepted by the mail transport and sent to {$recipient}. Check spam/junk if it does not arrive.");
        } catch (\Throwable $exception) {
            Log::error('Admin SMTP test email failed', [
                'recipient' => $recipient,
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'error' => $exception->getMessage(),
                'exception' => $exception::class,
            ]);

            return back()->withInput()->withErrors([
                'email' => 'Email delivery failed: ' . $exception->getMessage(),
            ]);
        }
    }
}