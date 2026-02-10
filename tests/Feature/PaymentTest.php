<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Mockery;
use Tests\TestCase;
use Razorpay\Api\Api;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('services.razorpay.key', 'test_key');
        Config::set('services.razorpay.secret', 'test_secret');
    }

    protected function createCustomer()
    {
        return Customer::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'phone' => '1234567890',
            'email' => 'test@example.com',
            'is_phone_verified' => true,
        ]);
    }

    protected function createPlan()
    {
        return Plan::create([
            'name' => 'Test Plan',
            'description' => 'Test Description',
            'purifier_type' => 'ro',
            'litres' => 100,
            'price' => 100.00,
            'duration_in_days' => 30,
            'is_active' => true,
        ]);
    }

    public function test_create_order_successfully()
    {
        $customer = $this->createCustomer();
        $plan = $this->createPlan();
        $subscription = Subscription::create([
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'status' => 'pending',
        ]);

        $mockOrder = Mockery::mock('Razorpay\Api\Order');
        $mockOrder->shouldReceive('create')->andReturn((object) ['id' => 'order_123']);

        $mockApi = Mockery::mock(Api::class);
        $mockApi->order = $mockOrder;

        $this->instance(Api::class, $mockApi);

        $response = $this->actingAs($customer, 'sanctum')
            ->postJson("/api/subscriptions/{$subscription->id}/pay");

        $response->assertStatus(200)
            ->assertJson([
                'order_id' => 'order_123',
                'amount' => 10000,
                'currency' => 'INR',
            ]);

        $this->assertDatabaseHas('payments', [
            'subscription_id' => $subscription->id,
            'razorpay_order_id' => 'order_123',
            'amount' => 100.00,
            'status' => 'pending',
        ]);
    }

    public function test_verify_payment_successfully()
    {
        $customer = $this->createCustomer();
        $plan = $this->createPlan();
        $subscription = Subscription::create([
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'status' => 'pending',
        ]);

        $payment = Payment::create([
            'subscription_id' => $subscription->id,
            'razorpay_order_id' => 'order_123',
            'amount' => 100.00,
            'currency' => 'INR',
            'status' => 'pending',
        ]);

        $mockUtility = Mockery::mock('Razorpay\Api\Utility');
        $mockUtility->shouldReceive('verifyPaymentSignature')->once();

        $mockApi = Mockery::mock(Api::class);
        $mockApi->utility = $mockUtility;

        $this->instance(Api::class, $mockApi);

        $payload = [
            'razorpay_order_id' => 'order_123',
            'razorpay_payment_id' => 'pay_123',
            'razorpay_signature' => 'sig_123',
        ];

        $response = $this->actingAs($customer, 'sanctum')
            ->postJson('/api/payment/verify', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Payment successful and subscription activated',
            ]);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'completed',
            'razorpay_payment_id' => 'pay_123',
            'razorpay_signature' => 'sig_123',
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'payment_status' => 'completed',
            'status' => 'active',
        ]);
    }

    public function test_cannot_create_order_for_another_users_subscription()
    {
        $owner = $this->createCustomer();
        $attacker = Customer::create([
            'first_name' => 'Attacker',
            'last_name' => 'User',
            'phone' => '9999999999',
            'email' => 'attacker@example.com',
            'is_phone_verified' => true,
        ]);

        $plan = $this->createPlan();

        $subscription = Subscription::create([
            'customer_id' => $owner->id,
            'plan_id' => $plan->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($attacker, 'sanctum')
            ->postJson("/api/subscriptions/{$subscription->id}/pay");

        $response->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized access to subscription']);
    }
}
