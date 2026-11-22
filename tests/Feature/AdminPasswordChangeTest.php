<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_admin_can_change_password(): void
    {
        $admin = Admin::factory()->create(['password' => Hash::make('OldPass1!')]);

        $response = $this->actingAs($admin, 'admin')->put(route('admin.password.update'), [
            'current_password' => 'OldPass1!',
            'password' => 'NewStrong2@',
            'password_confirmation' => 'NewStrong2@',
        ]);

        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('NewStrong2@', $admin->fresh()->password));
    }

    public function test_admin_must_provide_correct_current_password(): void
    {
        $admin = Admin::factory()->create(['password' => Hash::make('OldPass1!')]);

        $response = $this->actingAs($admin, 'admin')->from(route('admin.password.change'))->put(route('admin.password.update'), [
            'current_password' => 'WrongPass1!',
            'password' => 'NewStrong2@',
            'password_confirmation' => 'NewStrong2@',
        ]);

        $response->assertRedirect(route('admin.password.change'));
        $response->assertSessionHasErrors('current_password');
    }

    public function test_admin_cannot_reuse_current_password(): void
    {
        $admin = Admin::factory()->create(['password' => Hash::make('OldPass1!')]);

        $response = $this->actingAs($admin, 'admin')->put(route('admin.password.update'), [
            'current_password' => 'OldPass1!',
            'password' => 'OldPass1!',
            'password_confirmation' => 'OldPass1!',
        ]);

        $response->assertSessionHasErrors('password');
    }
}
