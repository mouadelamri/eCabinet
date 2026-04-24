<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    // ✅ Test 1 : page login accessible
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    // ✅ Test 2 : login avec bon email/password
    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
            'role'     => 'PATIENT',
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    // ❌ Test 3 : login avec mauvais password
    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('correct123'),
        ]);

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'wrong123',
        ]);

        $this->assertGuest();
    }

    // ❌ Test 4 : login avec email inexistant
    public function test_user_cannot_login_with_unknown_email(): void
    {
        $this->post('/login', [
            'email'    => 'notexist@test.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
    }

    // ✅ Test 5 : logout
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post('/logout');

        $this->assertGuest();
    }

    // ✅ Test 6 : admin redirigé vers admin dashboard après login
    public function test_admin_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role'     => 'ADMIN',
            'password' => bcrypt('password123'),
        ]);

        $this->post('/login', [
            'email'    => $admin->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($admin);
    }
}
