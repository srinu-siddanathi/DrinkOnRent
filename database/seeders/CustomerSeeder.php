<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customers = [
            [
                'name' => 'John Doe',
                'phone' => '9876543210',
                'email' => 'john@example.com',
                'address' => '123 Main St, City',
                'is_phone_verified' => true,
            ],
            [
                'name' => 'Jane Smith',
                'phone' => '9876543211',
                'email' => 'jane@example.com',
                'address' => '456 Oak St, City',
                'is_phone_verified' => true,
            ],
            [
                'name' => 'Mike Johnson',
                'phone' => '9876543212',
                'email' => 'mike@example.com',
                'address' => '789 Pine St, City',
                'is_phone_verified' => true,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(
                ['phone' => $customer['phone']],
                $customer
            );
        }
    }
} 