<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\Messaging\SmsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Mockery;
use Tests\TestCase;

class SendRentalReminderSmsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-10-12 09:00:00');
        Config::set('services.msg91.reminder_template_id', 'reminder-template');
        Config::set('services.msg91.reminder_lookback_days', 7);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_it_does_not_remind_for_an_old_subscription_after_a_renewal(): void
    {
        $customer = Customer::create([
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'phone' => '1234567890',
            'is_phone_verified' => true,
        ]);
        $plan = Plan::create([
            'name' => 'Test Plan',
            'purifier_type' => 'ro',
            'litres' => 500,
            'price' => 499,
            'duration_in_days' => 30,
            'is_active' => true,
        ]);

        Subscription::create([
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'payment_status' => 'completed',
            'start_date' => now()->subDays(33),
            'end_date' => now()->subDays(3),
        ]);
        Subscription::create([
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'payment_status' => 'completed',
            'start_date' => now()->subDays(4),
            'end_date' => now()->addDays(26),
        ]);

        $smsService = Mockery::mock(SmsService::class);
        $smsService->shouldNotReceive('sendFlowSms');
        $this->app->instance(SmsService::class, $smsService);

        $this->artisan('sms:send-rental-reminders')->assertExitCode(0);
    }
}
