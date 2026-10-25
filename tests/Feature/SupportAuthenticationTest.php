<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Organization;
use App\Models\User;
use App\Support\HelpdeskSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_and_auth_pages_render(): void
    {
        $this->get('/nl/support')->assertOk()->assertSee('JL-Tech');
        $this->get('/nl/support/login')->assertOk();
        $this->get('/nl/support/register')->assertOk();
        $this->get('/nl/support/forgot-password')->assertOk();
    }

    public function test_registration_always_creates_a_customer(): void
    {
        $this->post('/nl/support/register', [
            'name' => 'Jan Peeters',
            'company' => 'Peeters BV',
            'email' => 'jan@example.com',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
            'support_role' => 'admin',
        ])->assertRedirect(support_route('tickets.index'));

        $user = User::where('email', 'jan@example.com')->firstOrFail();
        $this->assertSame(UserRole::Customer, $user->support_role);
        $this->assertSame('Peeters BV', $user->company);
        $this->assertAuthenticatedAs($user);
    }

    public function test_customer_logs_in_to_the_portal(): void
    {
        $customer = User::factory()->customer()->create();

        $this->post('/nl/support/login', ['email' => $customer->email, 'password' => 'password'])
            ->assertRedirect(support_route('tickets.index'));

        $this->assertAuthenticatedAs($customer);
    }

    public function test_agents_are_sent_to_the_agent_panel_after_login(): void
    {
        $agent = User::factory()->agent()->create();

        $this->post('/nl/support/login', ['email' => $agent->email, 'password' => 'password'])
            ->assertRedirect('/agent');
    }

    public function test_inactive_users_cannot_log_in(): void
    {
        $user = User::factory()->inactive()->create();

        $this->post('/nl/support/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_panel_access_follows_the_role(): void
    {
        $client = User::factory()->customer()->create();
        $this->actingAs($client)->get('/agent')->assertForbidden();
        $this->actingAs($client)->get('/admin')->assertForbidden();

        $agent = User::factory()->agent()->create();
        $this->actingAs($agent)->get('/agent')->assertOk();
        $this->actingAs($agent)->get('/admin')->assertForbidden();

        // Helpdesk administrators work in /agent; /admin needs a website role.
        $helpdeskAdmin = User::factory()->helpdeskAdmin()->create();
        $this->actingAs($helpdeskAdmin)->get('/agent')->assertOk();
        $this->actingAs($helpdeskAdmin)->get('/admin')->assertForbidden();

        // Website super administrators may use both panels.
        $superAdmin = User::factory()->admin()->create();
        $this->actingAs($superAdmin)->get('/agent')->assertOk();
        $this->actingAs($superAdmin)->get('/admin')->assertOk();

        $this->actingAs(User::factory()->helpdeskAdmin()->inactive()->create())->get('/agent')->assertForbidden();
    }

    public function test_the_portal_follows_the_language_in_the_url(): void
    {
        $customer = User::factory()->customer()->create(['locale' => 'nl']);

        $this->actingAs($customer)->get('/nl/support/tickets')->assertOk()->assertSee('Mijn tickets');
        $this->actingAs($customer)->get('/en/support/tickets')->assertOk()->assertSee('My tickets');
    }

    public function test_registration_can_be_switched_off(): void
    {
        app(HelpdeskSettings::class)->save(['allow_registration' => false]);

        $this->get('/nl/support/register')->assertNotFound();
        $this->post('/nl/support/register', ['name' => 'X', 'email' => 'x@example.com', 'password' => 'secret-password', 'password_confirmation' => 'secret-password'])->assertNotFound();
        $this->get('/nl/support/login')->assertOk()->assertDontSee(support_route('register'));
        $this->assertDatabaseMissing('users', ['email' => 'x@example.com']);
    }

    public function test_new_clients_join_their_organization_by_email_domain(): void
    {
        $organization = Organization::factory()->create(['domain' => 'Peeters.be']);

        $this->post('/nl/support/register', [
            'name' => 'An Peeters',
            'email' => 'an@peeters.be',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ]);

        $this->assertTrue(User::where('email', 'an@peeters.be')->sole()->organization->is($organization));
    }
}
