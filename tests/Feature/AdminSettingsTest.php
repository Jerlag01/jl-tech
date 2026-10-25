<?php

namespace Tests\Feature;

use App\Filament\Pages\PageContent;
use App\Filament\Pages\Settings as SettingsPage;
use App\Models\Content;
use App\Models\User;
use App\Support\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Livewire\Livewire;
use Tests\TestCase;

class AdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_are_saved_to_the_website_content(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(SettingsPage::class)
            ->fillForm([
                'company.name' => 'JL-Tech BV',
                'company.tagline.nl' => 'Nieuwe slogan',
                'stats.employees' => '',
                'pages.esg' => false,
                'contact.phone' => '+32 3 000 00 00',
                'appearance.accent' => '#123456',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('JL-Tech BV', Content::raw('company.name'));
        $this->assertSame('Nieuwe slogan', Content::raw('company.tagline')['nl']);
        $this->assertSame('', Content::raw('stats.employees'));
        $this->assertFalse(content_enabled('pages.esg'));
        $this->assertSame('+32 3 000 00 00', Content::raw('contact.phone'));
        $this->assertStringContainsString('--accent:#123456', Settings::themeCss());

        $this->get('/nl')->assertSee('--accent:#123456', false)->assertSee('JL-Tech BV');
    }

    public function test_only_the_tabs_the_user_may_edit_are_saved(): void
    {
        $this->actingAs(User::factory()->withPermissions(['settings' => ['contact']])->create());

        Livewire::test(SettingsPage::class)
            ->fillForm(['contact.phone' => '+32 2 111 11 11'])
            ->set('data.company.name', 'Gehackt')
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('+32 2 111 11 11', Content::raw('contact.phone'));
        $this->assertSame('JL-Tech', Content::raw('company.name'));
    }

    public function test_the_smtp_password_is_stored_encrypted_and_kept_when_left_empty(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(SettingsPage::class)
            ->fillForm(['mail.mailer' => 'smtp', 'mail.host' => 'smtp.example.com', 'mail.port' => 587, 'mail.password' => 'geheim'])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertSet('data.mail.password', null);

        $this->assertSame('geheim', Crypt::decryptString(Content::raw('mail.password')));

        Livewire::test(SettingsPage::class)->call('save');
        $this->assertSame('geheim', Crypt::decryptString(Content::raw('mail.password')));

        Settings::applyMailConfig();
        $this->assertSame('smtp', config('mail.default'));
        $this->assertSame('smtp.example.com', config('mail.mailers.smtp.host'));
        $this->assertSame('geheim', config('mail.mailers.smtp.password'));
    }

    public function test_page_texts_are_saved_and_shown_on_the_website(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(PageContent::class)
            ->fillForm(['home.hero_title_1.nl' => 'Nieuwe titel', 'home.hero_title_1.en' => 'New title'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->get('/nl')->assertSee('Nieuwe titel');
        $this->get('/en')->assertSee('New title');
    }

    public function test_users_who_may_only_view_page_texts_cannot_save_them(): void
    {
        $this->actingAs(User::factory()->withPermissions(['page_content' => ['view']])->create());

        $this->get('/admin/page-texts')->assertOk();

        Livewire::test(PageContent::class)
            ->set('data.home.hero_title_1.nl', 'Gehackt')
            ->call('save')
            ->assertForbidden();
    }
}
