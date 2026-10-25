<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_every_content_and_settings_group_can_be_opened(): void
    {
        foreach (array_keys(config('content')) as $group) {
            $this->get("/admin/content/{$group}")->assertOk();
        }
    }

    public function test_unknown_groups_return_not_found(): void
    {
        $this->get('/admin/content/does-not-exist')->assertNotFound();
    }

    public function test_translated_and_plain_fields_are_saved(): void
    {
        $this->put('/admin/content/company', [
            'company__name' => 'JL-Tech BV',
            'company__tagline' => ['nl' => 'Nieuwe slogan', 'en' => 'New tagline'],
        ])->assertRedirect();

        $this->assertSame('JL-Tech BV', Content::raw('company.name'));
        $this->assertSame(['nl' => 'Nieuwe slogan', 'en' => 'New tagline'], Content::raw('company.tagline'));
    }

    public function test_page_toggles_are_saved(): void
    {
        $this->put('/admin/content/pages', ['pages__esg' => '0', 'pages__jobs' => '1'])->assertRedirect();

        $this->assertFalse(content_enabled('pages.esg'));
        $this->assertTrue(content_enabled('pages.jobs'));
    }

    public function test_images_can_be_uploaded_and_removed(): void
    {
        Storage::fake('public');

        $this->put('/admin/content/branding', ['branding__logo' => UploadedFile::fake()->image('logo.png')]);

        $path = Content::raw('branding.logo');
        Storage::disk('public')->assertExists($path);

        $this->put('/admin/content/branding', ['remove_branding__logo' => '1']);

        Storage::disk('public')->assertMissing($path);
        $this->assertSame('', Content::raw('branding.logo'));
    }

    public function test_invalid_email_settings_are_rejected(): void
    {
        $this->put('/admin/content/contact', ['contact__email' => 'not-an-email'])
            ->assertSessionHasErrors('contact__email');
    }
}
