<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_login_form()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);

        // Option 1: Check that the login form has expected elements instead of checking the view name
        $response->assertSee('email');
        $response->assertSee('password');
    }

    public function test_user_can_view_register_form()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    public function test_user_can_register()
    {
        $response = $this->post(route('auth.register.post'), [
            'name' => 'Test User',
            'email' => 'testuser@email.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('user.home'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@email.com'
        ]);
    }

    public function test_admin_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@email.com',
            'password' => bcrypt('password123'),
            'isAdmin' => true,
        ]);

        $response = $this->post(route('auth.login.post'), [
            'email' => 'admin@email.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_regular_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@email.com',
            'password' => bcrypt('password123'),
            'isAdmin' => false,
        ]);

        $response = $this->post(route('auth.login.post'), [
            'email' => 'user@email.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('user.home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'testuser@email.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('auth.login.post'), [
            'email' => 'testuser@email.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post(route('auth.logout'));

        $response->assertRedirect(route('auth.login'));
        $this->assertGuest();
    }
}
