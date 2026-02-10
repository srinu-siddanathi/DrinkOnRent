<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // RO Plans
        $roPlans = [
            [
                'name' => 'RO Basic',
                'description' => 'Basic RO water plan with 500 litres monthly supply',
                'purifier_type' => 'ro',
                'litres' => 500,
                'price' => 599,
                'duration_in_days' => 30,
                'is_active' => true
            ],
            [
                'name' => 'RO Premium',
                'description' => 'Premium RO water plan with 1000 litres monthly supply',
                'purifier_type' => 'ro',
                'litres' => 1000,
                'price' => 999,
                'duration_in_days' => 30,
                'is_active' => true
            ],
        ];

        // Alkaline Plans
        $alkalinePlans = [
            [
                'name' => 'Alkaline Basic',
                'description' => 'Basic Alkaline water plan with 500 litres monthly supply',
                'purifier_type' => 'alkaline',
                'litres' => 500,
                'price' => 699,
                'duration_in_days' => 30,
                'is_active' => true
            ],
            [
                'name' => 'Alkaline Premium',
                'description' => 'Premium Alkaline water plan with 1000 litres monthly supply',
                'purifier_type' => 'alkaline',
                'litres' => 1000,
                'price' => 1199,
                'duration_in_days' => 30,
                'is_active' => true
            ],
        ];

        // Create all plans
        foreach (array_merge($roPlans, $alkalinePlans) as $plan) {
            Plan::create($plan);
        }
    }
} 