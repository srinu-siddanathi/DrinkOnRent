<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_registration_flow()
    {
        // 1. Send OTP
        $response = $this->postJson('/api/send-otp', [
            'phone' => '1234567890',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['otp']);

        $otp = $response->json('otp');

        // 2. Verify OTP
        $response = $this->postJson('/api/verify-otp', [
            'phone' => '1234567890',
            'otp' => (string) $otp,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'customer']);

        $token = $response->json('token');
        $customer = $response->json('customer');

        // Verify customer created with no name (since we removed the default name logic)
        // Actually, the database column 'name' is gone.
        // And we didn't set first_name or last_name.
        $this->assertDatabaseHas('customers', [
            'phone' => '1234567890',
            'first_name' => null,
            'last_name' => null,
        ]);

        // 3. Register (Complete Profile)
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/register', [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'gender' => 'male',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Profile updated successfully',
                'customer' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john.doe@example.com',
                    'gender' => 'male',
                    'name' => 'John Doe', // Check accessor
                ],
            ]);

        $this->assertDatabaseHas('customers', [
            'phone' => '1234567890',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'gender' => 'male',
        ]);
    }

    public function test_registration_validation()
    {
        $customer = Customer::create([
            'phone' => '1234567890',
            'is_phone_verified' => true,
        ]);

        $token = $customer->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/register', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['first_name', 'last_name', 'email', 'gender']);
    }

    public function test_email_uniqueness()
    {
        Customer::create([
            'phone' => '0987654321',
            'email' => 'existing@example.com',
            'first_name' => 'Existing',
            'last_name' => 'User',
        ]);

        $customer = Customer::create([
            'phone' => '1234567890',
            'is_phone_verified' => true,
        ]);

        $token = $customer->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/register', [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'existing@example.com',
                'gender' => 'male',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
