<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::create([
            'name' => 'Basic Plan',
            'description' => 'Monthly water supply with 100 litres.',
            'duration_days' => 30,
            'litres_per_month' => 100,
            'price' => 500.00,
        ]);

        Plan::create([
            'name' => 'Standard Plan',
            'description' => 'Monthly water supply with 200 litres.',
            'duration_days' => 30,
            'litres_per_month' => 200,
            'price' => 800.00,
        ]);

        Plan::create([
            'name' => 'Premium Plan',
            'description' => 'Monthly water supply with 300 litres and free delivery.',
            'duration_days' => 30,
            'litres_per_month' => 300,
            'price' => 1200.00,
        ]);
    }
} 