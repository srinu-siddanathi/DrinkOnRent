<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run()
    {
        $customers = Customer::all();
        $plans = Plan::all();

        foreach ($customers as $customer) {
            // Create an active subscription
            Subscription::create([
                'customer_id' => $customer->id,
                'plan_id' => $plans->random()->id,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'status' => 'active',
                'litres_consumed' => rand(0, 50),
                'litres_remaining' => 100,
                'payment_status' => 'completed',
            ]);

            // Create a completed subscription
            Subscription::create([
                'customer_id' => $customer->id,
                'plan_id' => $plans->random()->id,
                'start_date' => now()->subDays(60),
                'end_date' => now()->subDays(30),
                'status' => 'expired',
                'litres_consumed' => 100,
                'litres_remaining' => 0,
                'payment_status' => 'completed',
            ]);
        }
    }
} 