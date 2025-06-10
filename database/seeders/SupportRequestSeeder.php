<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\SupportRequest;
use Illuminate\Database\Seeder;

class SupportRequestSeeder extends Seeder
{
    public function run()
    {
        $customers = Customer::all();
        $statuses = ['open', 'in_progress', 'resolved', 'closed'];
        $subjects = [
            'Water Quality Issue',
            'Delivery Delay',
            'Billing Question',
            'Technical Problem',
            'Service Request'
        ];

        foreach ($customers as $customer) {
            // Create 2-3 support requests per customer
            for ($i = 0; $i < rand(2, 3); $i++) {
                SupportRequest::create([
                    'customer_id' => $customer->id,
                    'subject' => $subjects[array_rand($subjects)],
                    'message' => 'This is a sample support request message describing the issue in detail.',
                    'status' => $statuses[array_rand($statuses)],
                    'admin_notes' => rand(0, 1) ? 'Admin notes about the resolution process.' : null,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
} 