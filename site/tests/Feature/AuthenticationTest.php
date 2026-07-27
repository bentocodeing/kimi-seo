<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Sign in')
            ->assertSee('name="email"', false)
            ->assertSee('name="password"', false);
    }

    public function test_guest_is_redirected_from_admin_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/ads')->assertRedirect('/login');
        $this->get('/admin/inquiries')->assertRedirect('/login');
    }

    public function test_wrong_credentials_are_rejected_with_session_errors(): void
    {
        User::factory()->create(['email' => 'admin@example.com']);

        $this->from('/login')->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ])->assertRedirect('/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_valid_credentials_reach_the_admin_dashboard(): void
    {
        $user = User::factory()->create(['email' => 'admin@example.com']);

        $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])->assertRedirect('/admin');

        $this->assertAuthenticatedAs($user);

        $this->get('/admin')->assertOk()->assertSee('Dashboard');
    }

    public function test_logout_works(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();

        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_registration_is_not_available(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_seeder_creates_admin_user_from_env(): void
    {
        $_ENV['ADMIN_EMAIL'] = $_SERVER['ADMIN_EMAIL'] = 'seeded@example.com';
        $_ENV['ADMIN_PASSWORD'] = $_SERVER['ADMIN_PASSWORD'] = 'seeded-secret';

        try {
            $this->seed();
        } finally {
            unset(
                $_ENV['ADMIN_EMAIL'], $_SERVER['ADMIN_EMAIL'],
                $_ENV['ADMIN_PASSWORD'], $_SERVER['ADMIN_PASSWORD'],
            );
        }

        $user = User::where('email', 'seeded@example.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('seeded-secret', $user->password));
    }
}
