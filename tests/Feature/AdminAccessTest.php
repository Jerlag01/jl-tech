<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/expertises')->assertRedirect('/admin/login');
    }

    public function test_login_page_is_available(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    #[TestWith(['/admin'])]
    #[TestWith(['/admin/page-texts'])]
    #[TestWith(['/admin/page-blocks'])]
    #[TestWith(['/admin/expertises'])]
    #[TestWith(['/admin/realisaties'])]
    #[TestWith(['/admin/posts'])]
    #[TestWith(['/admin/posts/create'])]
    #[TestWith(['/admin/clients'])]
    #[TestWith(['/admin/who-is-who'])]
    #[TestWith(['/admin/vacancies'])]
    #[TestWith(['/admin/contact-messages'])]
    #[TestWith(['/admin/profile'])]
    public function test_editors_can_manage_content(string $url): void
    {
        $this->actingAs(User::factory()->create())->get($url)->assertOk();
    }

    #[TestWith(['/admin/users'])]
    #[TestWith(['/admin/roles'])]
    #[TestWith(['/admin/settings'])]
    #[TestWith(['/admin/blocked-ips'])]
    #[TestWith(['/admin/footer-links'])]
    #[TestWith(['/admin/system-update'])]
    public function test_editors_cannot_manage_the_system(string $url): void
    {
        $this->actingAs(User::factory()->create())->get($url)->assertForbidden();
    }

    #[TestWith(['/admin/users'])]
    #[TestWith(['/admin/users/create'])]
    #[TestWith(['/admin/roles'])]
    #[TestWith(['/admin/settings'])]
    #[TestWith(['/admin/blocked-ips'])]
    #[TestWith(['/admin/footer-links'])]
    public function test_admins_can_manage_the_system(string $url): void
    {
        $this->actingAs(User::factory()->admin()->create())->get($url)->assertOk();
    }

    public function test_users_without_a_role_cannot_access_the_panel(): void
    {
        $this->actingAs(User::factory()->withoutRole()->create())->get('/admin')->assertForbidden();
    }

    #[TestWith(['en', 'Website settings', 'Contact messages'])]
    #[TestWith(['nl', 'Website-instellingen', 'Contactberichten'])]
    public function test_admin_panel_uses_the_users_language(string $locale, string $settingsLabel, string $messagesLabel): void
    {
        $this->actingAs(User::factory()->admin()->create(['locale' => $locale]))
            ->get('/admin')
            ->assertOk()
            ->assertSee("lang=\"{$locale}\"", escape: false)
            ->assertSee($settingsLabel)
            ->assertSee($messagesLabel);
    }

    public function test_the_create_user_command_assigns_a_role(): void
    {
        $this->artisan('admin:create', ['--name' => 'Jan', '--email' => 'jan@example.com', '--role' => 'Administrator', '--locale' => 'nl'])
            ->expectsQuestion('Password', 'long-enough-password')
            ->assertSuccessful();

        $this->assertTrue(User::query()->firstWhere('email', 'jan@example.com')->isSuperAdmin());
    }
}
