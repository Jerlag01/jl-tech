<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Employee;
use App\Models\FooterLink;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhoIsWhoAndNewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_who_is_who_shows_visible_people_only_when_there_are_some(): void
    {
        $this->get('/nl/wie-is-wie')->assertNotFound();

        Employee::factory()->create(['name' => 'An Peeters', 'job_title' => ['nl' => 'Zaakvoerder', 'en' => 'Managing director']]);
        Employee::factory()->hidden()->create(['name' => 'Verborgen Persoon']);

        $this->get('/en/who-is-who')->assertOk()->assertSee('An Peeters')->assertSee('Managing director')->assertDontSee('Verborgen Persoon');
        $this->get('/nl')->assertSee('/nl/wie-is-wie', false);

        Content::put('pages.who_is_who', '0');
        $this->get('/nl/wie-is-wie')->assertNotFound();
    }

    public function test_news_lists_published_posts_and_hides_drafts(): void
    {
        $this->get('/nl/nieuws')->assertNotFound();

        $post = Post::factory()->create();
        $draft = Post::factory()->draft()->create();
        $scheduled = Post::factory()->scheduled()->create();

        $this->get('/nl/nieuws')->assertOk()->assertSee($post->title['nl'])->assertDontSee($draft->title['nl'])->assertDontSee($scheduled->title['nl']);
        $this->get("/en/news/{$post->slug}")->assertOk()->assertSee($post->title['en']);
        $this->get("/nl/nieuws/{$draft->slug}")->assertNotFound();
        $this->get("/nl/nieuws/{$post->slug}")->assertSee('href="'.url("/en/news/{$post->slug}").'"', false);
    }

    public function test_visible_footer_links_appear_in_the_footer(): void
    {
        FooterLink::factory()->create(['label' => ['nl' => 'Voorwaarden', 'en' => 'Terms'], 'url' => 'https://example.com/terms']);
        FooterLink::factory()->hidden()->create(['label' => ['nl' => 'Verborgen link', 'en' => 'Hidden link']]);

        $this->get('/nl')->assertSee('Voorwaarden')->assertSee('https://example.com/terms')->assertDontSee('Verborgen link');
    }
}
