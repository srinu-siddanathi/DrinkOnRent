<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Service;
use App\Models\SupportRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportRequestApiTest extends TestCase
{
    use RefreshDatabase;

    protected function createCustomer(array $overrides = []): Customer
    {
        return Customer::create(array_merge([
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'phone' => '9000000000',
            'email' => 'test.customer@example.com',
            'is_phone_verified' => true,
        ], $overrides));
    }

    public function test_customer_can_view_support_request_with_admin_style_service_details(): void
    {
        $customer = $this->createCustomer();

        $supportRequest = SupportRequest::create([
            'customer_id' => $customer->id,
            'subject' => 'Need service',
            'message' => 'Please schedule maintenance',
            'status' => 'open',
        ]);

        $olderService = Service::create([
            'customer_id' => $customer->id,
            'support_request_id' => $supportRequest->id,
            'service_date' => '2026-01-10',
            'next_service_reminder' => 3,
            'expiry_date' => '2026-04-10',
            'spare_parts' => ['Sediment', 'Membrane'],
            'total_amount' => 250.00,
            'payment_mode' => 'cash',
            'images' => ['older.jpg'],
        ]);

        $latestService = Service::create([
            'customer_id' => $customer->id,
            'support_request_id' => $supportRequest->id,
            'service_date' => '2026-03-10',
            'next_service_reminder' => 6,
            'expiry_date' => '2026-09-10',
            'spare_parts' => ['Pump'],
            'total_amount' => 300.00,
            'payment_mode' => 'upi',
            'images' => ['latest-1.jpg', 'latest-2.jpg'],
        ]);

        $response = $this->actingAs($customer, 'sanctum')
            ->getJson("/api/support-requests/{$supportRequest->id}");

        $response->assertStatus(200)
            ->assertJsonPath('support_request.id', $supportRequest->id)
            ->assertJsonCount(2, 'support_request.services')
            ->assertJsonPath('support_request.services.0.id', $latestService->id)
            ->assertJsonPath('support_request.services.0.service_date', '10-03-2026')
            ->assertJsonPath('support_request.services.0.next_service_reminder', '6 Months')
            ->assertJsonPath('support_request.services.0.expiry_date', '10-09-2026')
            ->assertJsonPath('support_request.services.0.spare_parts.0', 'Pump')
            ->assertJsonPath('support_request.services.0.images.0', asset('uploads/service-images/latest-1.jpg'))
            ->assertJsonPath('support_request.services.1.id', $olderService->id)
            ->assertJsonPath('support_request.services.1.next_service_reminder', '3 Months');
    }

    public function test_customer_cannot_view_another_customers_support_request(): void
    {
        $owner = $this->createCustomer();
        $otherCustomer = $this->createCustomer([
            'phone' => '9111111111',
            'email' => 'other.customer@example.com',
        ]);

        $supportRequest = SupportRequest::create([
            'customer_id' => $owner->id,
            'subject' => 'Private request',
            'message' => 'Owner only',
            'status' => 'open',
        ]);

        $response = $this->actingAs($otherCustomer, 'sanctum')
            ->getJson("/api/support-requests/{$supportRequest->id}");

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Unauthorized access',
            ]);
    }

    public function test_support_request_show_returns_empty_services_array_when_none_exists(): void
    {
        $customer = $this->createCustomer();

        $supportRequest = SupportRequest::create([
            'customer_id' => $customer->id,
            'subject' => 'No services yet',
            'message' => 'Fresh request',
            'status' => 'open',
        ]);

        $response = $this->actingAs($customer, 'sanctum')
            ->getJson("/api/support-requests/{$supportRequest->id}");

        $response->assertStatus(200)
            ->assertJsonPath('support_request.id', $supportRequest->id)
            ->assertJsonPath('support_request.services', []);
    }
}
