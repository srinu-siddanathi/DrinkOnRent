<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user only if it doesn't exist
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        // Call all seeders
        $this->call([
            AdminSeeder::class,
            PlanSeeder::class,
            CustomerSeeder::class,
            SubscriptionSeeder::class,
            SupportRequestSeeder::class,
            PurifierSeeder::class,
        ]);
        
        // You can also call other seeders here
        // $this->call(OtherSeeder::class);
    }
}
