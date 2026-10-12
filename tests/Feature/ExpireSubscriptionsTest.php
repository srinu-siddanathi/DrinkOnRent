<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpireSubscriptionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_expires_only_active_subscriptions_past_their_end_date(): void
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

        $pastDue = Subscription::create([
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'payment_status' => 'completed',
            'start_date' => now()->subDays(31),
            'end_date' => now()->subMinute(),
        ]);
        $notYetDue = Subscription::create([
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'payment_status' => 'completed',
            'start_date' => now(),
            'end_date' => now()->addDay(),
        ]);

        $this->artisan('subscriptions:expire')->assertExitCode(0);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $pastDue->id,
            'status' => 'expired',
        ]);
        $this->assertDatabaseHas('subscriptions', [
            'id' => $notYetDue->id,
            'status' => 'active',
        ]);
    }
}
