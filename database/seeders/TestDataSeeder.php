<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Purifier;
use App\Models\Subscription;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Create or find a test customer
        $customer = Customer::firstOrCreate(
            ['phone' => '9876543210'],
            [
                'name' => 'Test Customer',
                'email' => 'test@example.com',
                'address' => 'Test Address, City',
                'is_phone_verified' => true,
            ]
        );

        // Create RO and Alkaline plans if they don't exist
        $roPlan = Plan::firstOrCreate(
            ['name' => 'RO Basic'],
            [
                'description' => 'Basic RO water plan with 500 litres monthly supply',
                'purifier_type' => 'ro',
                'litres' => 500,
                'price' => 599,
                'duration_in_days' => 30,
                'is_active' => true,
            ]
        );

        $alkalinePlan = Plan::firstOrCreate(
            ['name' => 'Alkaline Premium'],
            [
                'description' => 'Premium Alkaline water plan with 300 litres monthly supply',
                'purifier_type' => 'alkaline',
                'litres' => 300,
                'price' => 899,
                'duration_in_days' => 30,
                'is_active' => true,
            ]
        );

        // Delete existing purifiers and subscriptions for this customer
        $customer->purifiers()->delete();
        $customer->subscriptions()->delete();

        // Create purifiers for the customer
        $roPurifier = Purifier::create([
            'customer_id' => $customer->id,
            'serial_number' => 'RO' . rand(1000, 9999),
            'model' => 'RO Basic Model',
            'type' => 'ro',
            'installation_date' => now(),
            'last_service_date' => now()->subDays(15),
            'next_service_date' => now()->addDays(15),
        ]);

        $alkalinePurifier = Purifier::create([
            'customer_id' => $customer->id,
            'serial_number' => 'ALK' . rand(1000, 9999),
            'model' => 'Alkaline Premium Model',
            'type' => 'alkaline',
            'installation_date' => now(),
            'last_service_date' => now()->subDays(10),
            'next_service_date' => now()->addDays(20),
        ]);

        // Create active subscriptions
        Subscription::create([
            'customer_id' => $customer->id,
            'plan_id' => $roPlan->id,
            'purifier_id' => $roPurifier->id,
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(25),
            'status' => 'active',
            'payment_status' => 'completed',
            'litres_consumed' => 150,
            'litres_remaining' => 350,
        ]);

        Subscription::create([
            'customer_id' => $customer->id,
            'plan_id' => $alkalinePlan->id,
            'purifier_id' => $alkalinePurifier->id,
            'start_date' => now()->subDays(10),
            'end_date' => now()->addDays(20),
            'status' => 'active',
            'payment_status' => 'completed',
            'litres_consumed' => 200,
            'litres_remaining' => 100,
        ]);
    }
} 