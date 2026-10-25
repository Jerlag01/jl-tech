<?php

namespace Tests\Feature;

use App\Models\Service;
use Tests\TestCase;

class TranslationsTest extends TestCase
{
    public function test_it_picks_the_requested_locale(): void
    {
        $this->assertSame('Hallo', Service::pickTranslation(['nl' => 'Hallo', 'en' => 'Hello'], 'nl'));
        $this->assertSame('Hello', Service::pickTranslation(['nl' => 'Hallo', 'en' => 'Hello'], 'en'));
    }

    public function test_it_falls_back_to_another_filled_locale(): void
    {
        $this->assertSame('Hallo', Service::pickTranslation(['nl' => 'Hallo', 'en' => ''], 'en'));
        $this->assertSame('', Service::pickTranslation(['nl' => '', 'en' => null], 'en'));
    }

    public function test_plain_strings_are_returned_as_is(): void
    {
        $this->assertSame('plain', Service::pickTranslation('plain'));
        $this->assertSame('', Service::pickTranslation(null));
    }

    public function test_stats_labels_are_translated_and_empty_rows_skipped(): void
    {
        $service = new Service(['stats' => [
            ['value' => '10', 'label' => ['nl' => 'Jaar', 'en' => 'Years']],
            ['value' => '', 'label' => ['nl' => 'Leeg', 'en' => 'Empty']],
        ]]);

        $this->assertSame([['value' => '10', 'label' => 'Years']], $service->trStats('stats', 'en'));
    }
}
