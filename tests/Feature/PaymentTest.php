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

    public function test_customer_can_get_payment_history_with_subscription_and_plan_summary()
    {
        $customer = $this->createCustomer();
        $otherCustomer = Customer::create([
            'first_name' => 'Other',
            'last_name' => 'Customer',
            'phone' => '8888888888',
            'email' => 'other@example.com',
            'is_phone_verified' => true,
        ]);

        $plan = $this->createPlan();
        $otherPlan = Plan::create([
            'name' => 'Other Plan',
            'description' => 'Other Description',
            'purifier_type' => 'alkaline',
            'litres' => 80,
            'price' => 80.00,
            'duration_in_days' => 15,
            'is_active' => true,
        ]);

        $subscription = Subscription::create([
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(25),
        ]);

        $otherSubscription = Subscription::create([
            'customer_id' => $otherCustomer->id,
            'plan_id' => $otherPlan->id,
            'status' => 'pending',
        ]);

        $firstPayment = Payment::create([
            'subscription_id' => $subscription->id,
            'razorpay_order_id' => 'order_hist_1',
            'razorpay_payment_id' => 'pay_hist_1',
            'amount' => 100.00,
            'currency' => 'INR',
            'status' => 'completed',
        ]);

        $secondPayment = Payment::create([
            'subscription_id' => $subscription->id,
            'razorpay_order_id' => 'order_hist_2',
            'amount' => 100.00,
            'currency' => 'INR',
            'status' => 'pending',
        ]);

        Payment::create([
            'subscription_id' => $otherSubscription->id,
            'razorpay_order_id' => 'order_hist_other',
            'amount' => 80.00,
            'currency' => 'INR',
            'status' => 'failed',
        ]);

        $response = $this->actingAs($customer, 'sanctum')
            ->getJson('/api/payments/history');

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Payment history fetched successfully')
            ->assertJsonCount(2, 'payments')
            ->assertJsonPath('payments.0.subscription.id', $subscription->id)
            ->assertJsonPath('payments.0.plan.id', $plan->id)
            ->assertJsonPath('payments.1.subscription.id', $subscription->id)
            ->assertJsonPath('payments.1.plan.name', $plan->name);

        $paymentIds = collect($response->json('payments'))->pluck('id')->all();
        $this->assertContains($firstPayment->id, $paymentIds);
        $this->assertContains($secondPayment->id, $paymentIds);
        $this->assertCount(2, $paymentIds);
    }

    public function test_payment_history_returns_empty_list_for_customer_without_payments()
    {
        $customer = $this->createCustomer();

        $response = $this->actingAs($customer, 'sanctum')
            ->getJson('/api/payments/history');

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Payment history is empty')
            ->assertJsonPath('payments', []);
    }

    public function test_unauthenticated_user_cannot_access_payment_history()
    {
        $response = $this->getJson('/api/payments/history');

        $response->assertStatus(401);
    }
}
