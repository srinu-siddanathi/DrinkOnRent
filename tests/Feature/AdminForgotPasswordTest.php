<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_request_password_reset_otp(): void
    {
        $admin = Admin::factory()->create(['email' => 'reset@example.com']);

        $response = $this->post(route('admin.password.otp.send'), ['email' => $admin->email]);

        $response->assertRedirect(route('admin.password.reset.verify'));
        $response->assertSessionHas('admin_password_reset_email', $admin->email);

        $key = 'admin-password-reset:' . hash('sha256', $admin->email);
        $this->assertNotNull(Cache::get($key));
    }

    public function test_unknown_email_is_not_exposed(): void
    {
        $response = $this->post(route('admin.password.otp.send'), ['email' => 'missing@example.com']);

        $response->assertRedirect(route('admin.password.reset.verify'));
        $response->assertSessionDoesntHaveErrors();
    }

    public function test_admin_can_reset_password_with_valid_otp(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'reset@example.com',
            'password' => Hash::make('OldPass1!'),
        ]);

        $key = 'admin-password-reset:' . hash('sha256', $admin->email);
        Cache::put($key, ['otp_hash' => Hash::make('123456'), 'attempts' => 0], now()->addMinutes(10));

        $response = $this->withSession(['admin_password_reset_email' => $admin->email])
            ->post(route('admin.password.reset'), [
                'otp' => '123456',
                'password' => 'NewStrong2@',
                'password_confirmation' => 'NewStrong2@',
            ]);

        $response->assertRedirect(route('admin.login'));
        $this->assertTrue(Hash::check('NewStrong2@', $admin->fresh()->password));
        $this->assertNull(Cache::get($key));
    }

    public function test_admin_cannot_reset_password_with_wrong_otp(): void
    {
        $admin = Admin::factory()->create(['email' => 'reset@example.com']);
        $key = 'admin-password-reset:' . hash('sha256', $admin->email);
        Cache::put($key, ['otp_hash' => Hash::make('123456'), 'attempts' => 0], now()->addMinutes(10));

        $response = $this->withSession(['admin_password_reset_email' => $admin->email])
            ->from(route('admin.password.reset.verify'))
            ->post(route('admin.password.reset'), [
                'otp' => '654321',
                'password' => 'NewStrong2@',
                'password_confirmation' => 'NewStrong2@',
            ]);

        $response->assertRedirect(route('admin.password.reset.verify'));
        $response->assertSessionHasErrors('otp');
    }
}
