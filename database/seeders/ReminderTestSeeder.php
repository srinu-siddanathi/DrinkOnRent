<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Database\Seeder;

class ReminderTestSeeder extends Seeder
{
    // Creates a customer with an active subscription expiring soon, for testing sms:send-rental-reminders / test-reminder-sms
    public function run(): void
    {
        $customer = Customer::updateOrCreate(
            ['phone' => '9999900001'],
            [
                'first_name' => 'Test',
                'last_name' => 'Reminder',
                'is_phone_verified' => true,
            ]
        );

        $plan = Plan::firstOrCreate(
            ['name' => 'Reminder Test Plan'],
            [
                'purifier_type' => 'ro',
                'litres' => 50,
                'price' => 499,
                'duration_in_days' => 30,
                'is_active' => true,
            ]
        );

        $subscription = Subscription::create([
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'payment_status' => 'completed',
            'start_date' => now()->subDays(29),
            'end_date' => now()->addDay(),
            'litres_remaining' => $plan->litres,
        ]);

        $this->command->info("Seeded customer #{$customer->id} (phone: {$customer->phone}) with subscription #{$subscription->id} expiring on {$subscription->end_date->toDateString()}.");
    }
}
