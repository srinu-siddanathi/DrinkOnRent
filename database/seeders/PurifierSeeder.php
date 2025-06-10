<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purifier;
use App\Models\Customer;

class PurifierSeeder extends Seeder
{
    public function run()
    {
        // Get some customers for assigning purifiers
        $customers = Customer::take(3)->get();

        // RO Purifiers
        $roPurifiers = [
            [
                'serial_number' => 'RO001',
                'model' => 'RO Premium 2024',
                'type' => 'ro',
                'status' => 'assigned',
                'customer_id' => $customers[0]->id,
                'installation_date' => '2024-01-15',
                'last_service_date' => '2024-02-15',
                'next_service_date' => '2024-05-15',
            ],
            [
                'serial_number' => 'RO002',
                'model' => 'RO Premium 2024',
                'type' => 'ro',
                'status' => 'available',
                'installation_date' => null,
                'last_service_date' => null,
                'next_service_date' => null,
            ],
            [
                'serial_number' => 'RO003',
                'model' => 'RO Premium 2024',
                'type' => 'ro',
                'status' => 'maintenance',
                'last_service_date' => '2024-03-01',
                'next_service_date' => '2024-03-15',
            ],
        ];

        // Alkaline Purifiers
        $alkalinePurifiers = [
            [
                'serial_number' => 'ALK001',
                'model' => 'Alkaline Plus 2024',
                'type' => 'alkaline',
                'status' => 'assigned',
                'customer_id' => $customers[1]->id,
                'installation_date' => '2024-02-01',
                'last_service_date' => '2024-03-01',
                'next_service_date' => '2024-06-01',
            ],
            [
                'serial_number' => 'ALK002',
                'model' => 'Alkaline Plus 2024',
                'type' => 'alkaline',
                'status' => 'assigned',
                'customer_id' => $customers[2]->id,
                'installation_date' => '2024-02-15',
                'last_service_date' => '2024-03-15',
                'next_service_date' => '2024-06-15',
            ],
            [
                'serial_number' => 'ALK003',
                'model' => 'Alkaline Plus 2024',
                'type' => 'alkaline',
                'status' => 'available',
                'installation_date' => null,
                'last_service_date' => null,
                'next_service_date' => null,
            ],
        ];

        // Create all purifiers
        foreach (array_merge($roPurifiers, $alkalinePurifiers) as $purifier) {
            Purifier::create($purifier);
        }
    }
} 