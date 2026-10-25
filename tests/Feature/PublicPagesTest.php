<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Content;
use App\Models\JobOffer;
use App\Models\Project;
use App\Models\Service;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_to_the_default_locale(): void
    {
        $this->get('/')->assertRedirect('/nl');
    }

    /**
     * @return array<string, array{string}>
     */
    public static function pageUrls(): array
    {
        $urls = [];

        // Data providers run before the application boots, so read the config file directly.
        $pages = (require dirname(__DIR__, 2).'/config/site.php')['pages'];

        // "Who is who" and "News" are hidden while empty; WhoIsWhoAndNewsTest covers them.
        unset($pages['who_is_who'], $pages['news']);

        foreach ($pages as $page => $slugs) {
            foreach ($slugs as $locale => $slug) {
                $urls["{$locale}.{$page}"] = [trim("/{$locale}/{$slug}", '/')];
            }
        }

        return $urls;
    }

    #[DataProvider('pageUrls')]
    public function test_every_page_renders_with_seeded_content(string $url): void
    {
        $this->seed(SiteContentSeeder::class);

        $this->get('/'.$url)->assertOk();
    }

    #[DataProvider('pageUrls')]
    public function test_every_page_renders_on_an_empty_database(string $url): void
    {
        $this->get('/'.$url)->assertOk();
    }

    public function test_pages_use_the_language_of_the_url(): void
    {
        $this->get('/nl')->assertOk()->assertSee('lang="nl"', false)->assertSee('Realisaties');
        $this->get('/en')->assertOk()->assertSee('lang="en"', false)->assertSee('Projects');
    }

    public function test_home_page_shows_services_featured_project_and_clients(): void
    {
        Service::factory()->create(['title' => ['nl' => 'Openbare verlichting', 'en' => 'Public lighting']]);
        Project::factory()->featured()->create(['title' => ['nl' => 'Uitgelicht werk', 'en' => 'Featured work']]);
        Client::factory()->create(['name' => 'Acme Partner']);

        $this->get('/en')
            ->assertOk()
            ->assertSee('Public lighting')
            ->assertSee('Featured work')
            ->assertSee('Acme Partner');
    }

    public function test_hidden_services_are_not_shown(): void
    {
        Service::factory()->hidden()->create(['title' => ['nl' => 'Verborgen dienst', 'en' => 'Hidden service']]);

        $this->get('/nl/expertises')->assertOk()->assertDontSee('Verborgen dienst');
    }

    public function test_an_empty_translation_falls_back_to_the_other_language(): void
    {
        Service::factory()->create(['title' => ['nl' => 'Alleen Nederlands', 'en' => '']]);

        $this->get('/en/services')->assertOk()->assertSee('Alleen Nederlands');
    }

    public function test_edited_content_is_shown_on_the_website(): void
    {
        Content::put('home.hero_title_1', ['nl' => 'Nieuwe titel', 'en' => 'New title']);

        $this->get('/nl')->assertSee('Nieuwe titel');
        $this->get('/en')->assertSee('New title');
    }

    public function test_key_figures_without_a_value_are_hidden(): void
    {
        Content::put('stats.employees', '');
        Content::put('stats.vehicles', '');
        Content::put('stats.projects', '');
        Content::put('company.founded_year', '');

        $this->get('/en')->assertOk()
            ->assertDontSee('hero-stats-card', false)
            ->assertDontSee('figures-grid', false);
        $this->get('/en/team-and-resources')->assertOk()
            ->assertDontSee('>±<', false)
            ->assertDontSee('fleet-total', false);
        $this->get('/en/careers')->assertOk()
            ->assertSee('no-stats', false);
    }

    public function test_only_the_key_figures_with_a_value_are_shown(): void
    {
        Content::put('stats.employees', '42');
        Content::put('stats.vehicles', '0');
        Content::put('stats.projects', '');

        $this->get('/en')->assertOk()
            ->assertSee('±42')
            ->assertSee('figures-grid count-2', false)
            ->assertDontSee(content('home.fig_vehicles_label'))
            ->assertDontSee(content('home.fig_projects_label'));
    }

    public function test_disabled_optional_pages_return_not_found_and_leave_the_menu(): void
    {
        Content::put('pages.esg', '0');

        $this->get('/nl/esg')->assertNotFound();
        $this->get('/nl')->assertDontSee('/nl/esg');
    }

    public function test_jobs_page_lists_active_offers_or_shows_the_empty_state(): void
    {
        $this->get('/en/careers')->assertOk()->assertSee('No openings at the moment');

        JobOffer::factory()->create(['title' => ['nl' => 'Elektricien', 'en' => 'Electrician']]);

        $this->get('/en/careers')->assertOk()->assertSee('Electrician')->assertDontSee('No openings at the moment');
    }

    public function test_language_switcher_links_to_the_same_page_in_the_other_language(): void
    {
        $this->get('/nl/realisaties')->assertSee('href="'.url('/en/projects').'"', false);
    }
}
