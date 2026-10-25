<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/services')->assertRedirect('/admin/login');
    }

    public function test_an_admin_can_log_in_and_out(): void
    {
        $user = User::factory()->create(['password' => 'secret-password']);

        $this->post('/admin/login', ['email' => $user->email, 'password' => 'secret-password'])
            ->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);

        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_wrong_credentials_are_rejected(): void
    {
        $user = User::factory()->create(['password' => 'secret-password']);

        $this->post('/admin/login', ['email' => $user->email, 'password' => 'wrong'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_the_admin_create_command_creates_a_user(): void
    {
        $this->artisan('admin:create', ['--name' => 'Admin', '--email' => 'admin@example.com', '--password' => 'long-enough-pw'])
            ->assertSuccessful();

        $this->assertDatabaseHas('users', ['email' => 'admin@example.com']);
    }
}
