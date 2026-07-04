<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_official_can_login_with_email(): void
    {
        $this->post('/login', [
            'login' => 'admin@grmswift.local',
            'password' => 'Admin@123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticated();
    }

    public function test_official_can_login_with_mobile(): void
    {
        $user = User::where('email', 'ssgc@grmswift.local')->first();

        $this->post('/login', [
            'login' => $user->mobile,
            'password' => 'Password@123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_wrong_password_is_rejected(): void
    {
        $this->post('/login', [
            'login' => 'admin@grmswift.local',
            'password' => 'wrong',
        ])->assertSessionHasErrors('login');

        $this->assertGuest();
    }

    public function test_guest_cannot_access_admin(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
    }

    public function test_non_admin_role_cannot_access_masters(): void
    {
        $ssgc = User::where('email', 'ssgc@grmswift.local')->first();

        $this->actingAs($ssgc)->get('/admin/districts')->assertForbidden();
    }
}
