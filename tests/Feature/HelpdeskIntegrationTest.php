<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Database\Seeders\HelpdeskDefaultsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

/**
 * The helpdesk living inside the website: shared users, permissions, navigation and settings.
 */
class HelpdeskIntegrationTest extends TestCase
{
    use RefreshDatabase;

    #[TestWith(['/admin/departments'])]
    #[TestWith(['/admin/help-topics'])]
    #[TestWith(['/admin/sla-plans'])]
    #[TestWith(['/admin/teams'])]
    #[TestWith(['/admin/agents'])]
    public function test_helpdesk_configuration_needs_the_helpdesk_permission(string $url): void
    {
        $this->actingAs(User::factory()->create())->get($url)->assertForbidden();
        $this->actingAs(User::factory()->withPermissions(['helpdesk' => ['view']])->create())->get($url)->assertOk();
    }

    public function test_website_users_screen_lists_administrators_only(): void
    {
        $client = User::factory()->customer()->create(['name' => 'Klant Peeters']);
        $agent = User::factory()->agent()->create(['name' => 'Agent Janssens']);

        $this->actingAs(User::factory()->admin()->create())
            ->get('/admin/users')
            ->assertOk()
            ->assertDontSee('Klant Peeters')
            ->assertDontSee('Agent Janssens');

        $this->get("/admin/users/{$client->id}/edit")->assertNotFound();
    }

    public function test_the_site_menu_links_to_the_support_portal(): void
    {
        $this->get('/nl')->assertOk()->assertSee('href="'.url('/nl/support').'"', false);
        $this->get('/en')->assertOk()->assertSee('href="'.url('/en/support').'"', false);
    }

    #[TestWith(['nl', 'Kies het onderwerp'])]
    #[TestWith(['en', 'Choose the topic'])]
    public function test_the_portal_home_shows_the_default_help_topics(string $locale, string $heading): void
    {
        $this->seed(HelpdeskDefaultsSeeder::class);

        $this->get("/{$locale}/support")->assertOk()->assertSee($heading);
        $this->assertSame(3, Department::query()->count());
    }

    public function test_guests_are_sent_to_the_portal_login_in_their_language(): void
    {
        $this->get('/en/support/tickets')->assertRedirect('/en/support/login');
        $this->get('/nl/support/tickets')->assertRedirect('/nl/support/login');
        $this->get('/agent')->assertRedirect('/agent/login');
    }
}
