<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KnowledgeBaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_articles_are_listed_searched_and_shown_in_the_visitor_language(): void
    {
        $category = FaqCategory::factory()->create(['name' => ['nl' => 'Laadpalen', 'en' => 'Charging stations']]);
        $faq = Faq::factory()->for($category, 'category')->create([
            'question' => ['nl' => 'Hoe reset ik mijn laadpaal?', 'en' => 'How do I reset my charging station?'],
            'answer' => ['nl' => 'Zet de automaat 10 seconden uit.'],
        ]);

        $this->get('/nl/support/kb')->assertOk()->assertSee('Laadpalen')->assertSee('Hoe reset ik mijn laadpaal?');
        $this->get('/nl/support/kb?search=reset')->assertSee('1 resultaat');
        $this->get('/nl/support/kb?search=zonnepaneel')->assertSee('Geen resultaten');
        $this->get(support_route('kb.category', $category))->assertOk()->assertSee('Zet de automaat');
        $this->get(support_route('kb.show', $faq))->assertOk()->assertSee('Zet de automaat');

        // English title, Dutch answer as fallback.
        $this->get(support_route('kb.show', $faq, 'en'))->assertSee('How do I reset my charging station?')->assertSee('Zet de automaat');
    }

    public function test_unpublished_articles_and_private_categories_stay_hidden(): void
    {
        $private = FaqCategory::factory()->create(['is_public' => false]);
        $internalFaq = Faq::factory()->for($private, 'category')->create(['question' => ['nl' => 'Interne procedure']]);
        $draft = Faq::factory()->create(['is_published' => false, 'question' => ['nl' => 'Nog niet klaar']]);

        $this->get('/nl/support/kb')->assertDontSee('Interne procedure')->assertDontSee('Nog niet klaar');
        $this->get(support_route('kb.show', $internalFaq))->assertNotFound();
        $this->get(support_route('kb.show', $draft))->assertNotFound();
        $this->get(support_route('kb.category', $private))->assertNotFound();
    }
}
