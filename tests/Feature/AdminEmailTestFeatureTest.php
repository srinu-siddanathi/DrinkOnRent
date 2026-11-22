<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminEmailTestFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_admin_can_open_email_test_page(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.settings.email-test'));

        $response->assertOk();
        $response->assertSee('SMTP Email Test');
    }

    public function test_admin_can_send_test_email(): void
    {
        Mail::fake();
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->from(route('admin.settings.email-test'))
            ->post(route('admin.settings.email-test.send'), [
                'email' => 'recipient@example.com',
            ]);

        $response->assertRedirect(route('admin.settings.email-test'));
        $response->assertSessionHas('success');
    }

    public function test_email_test_routes_require_admin_login(): void
    {
        $this->get(route('admin.settings.email-test'))
            ->assertRedirect(route('login'));
    }
}
