<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\ContactMessage;
use App\Models\Item;
use App\Models\JobOffer;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCollectionsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->actingAs($this->admin);
    }

    public function test_admin_screens_render(): void
    {
        $service = Service::factory()->create();
        $project = Project::factory()->create(['service_id' => $service->id]);
        $client = Client::factory()->create();
        $job = JobOffer::factory()->create();
        $item = Item::factory()->create();
        $message = ContactMessage::factory()->create();

        $urls = [
            '/admin',
            '/admin/services', '/admin/services/create', "/admin/services/{$service->id}/edit",
            '/admin/projects', '/admin/projects/create', "/admin/projects/{$project->id}/edit",
            '/admin/clients', '/admin/clients/create', "/admin/clients/{$client->id}/edit",
            '/admin/jobs', '/admin/jobs/create', "/admin/jobs/{$job->id}/edit",
            "/admin/items/{$item->id}/edit",
            '/admin/messages', "/admin/messages/{$message->id}",
            '/admin/users', '/admin/users/create', "/admin/users/{$this->admin->id}/edit",
        ];

        foreach (array_keys(config('site.item_groups')) as $group) {
            $urls[] = "/admin/blocks/{$group}";
            $urls[] = "/admin/blocks/{$group}/create";
        }

        foreach ($urls as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_a_service_can_be_created_with_an_image_and_stats(): void
    {
        Storage::fake('public');

        $this->post('/admin/services', [
            'title' => ['nl' => 'Glasvezel', 'en' => 'Fibre'],
            'features' => ['nl' => "Eén\nTwee", 'en' => ''],
            'stats' => [
                ['value' => '50+', 'label' => ['nl' => 'Projecten', 'en' => 'Projects']],
                ['value' => '', 'label' => ['nl' => 'leeg', 'en' => '']],
            ],
            'icon' => 'fiber',
            'image' => UploadedFile::fake()->image('fibre.jpg'),
            'active' => '1',
        ])->assertRedirect('/admin/services');

        $service = Service::query()->sole();
        $this->assertSame('fibre', $service->slug);
        $this->assertSame(['Eén', 'Twee'], $service->trLines('features', 'nl'));
        $this->assertCount(1, $service->stats);
        Storage::disk('public')->assertExists($service->image);
    }

    public function test_a_title_is_required_in_at_least_one_language(): void
    {
        $this->post('/admin/services', ['title' => ['nl' => '', 'en' => '']])
            ->assertSessionHasErrors(['title.nl', 'title.en']);

        $this->post('/admin/services', ['title' => ['nl' => 'Alleen NL', 'en' => '']])
            ->assertSessionDoesntHaveErrors();
    }

    public function test_only_one_project_can_be_featured(): void
    {
        $first = Project::factory()->featured()->create();
        $second = Project::factory()->create();

        $this->put("/admin/projects/{$second->id}", [
            'title' => ['nl' => 'Nieuw uitgelicht', 'en' => ''],
            'featured' => '1',
            'active' => '1',
        ])->assertRedirect('/admin/projects');

        $this->assertFalse($first->fresh()->featured);
        $this->assertTrue($second->fresh()->featured);
    }

    public function test_page_blocks_are_saved_in_their_group(): void
    {
        $this->post('/admin/blocks/timeline', [
            'value' => '2020',
            'title' => ['nl' => 'Mijlpaal', 'en' => 'Milestone'],
            'body' => ['nl' => 'Tekst', 'en' => 'Text'],
            'active' => '1',
        ])->assertRedirect('/admin/blocks/timeline');

        $this->assertDatabaseHas('items', ['group' => 'timeline', 'value' => '2020']);
        $this->get('/admin/blocks/unknown')->assertNotFound();
    }

    public function test_items_can_be_deleted(): void
    {
        $client = Client::factory()->create();
        $job = JobOffer::factory()->create();

        $this->delete("/admin/clients/{$client->id}")->assertRedirect('/admin/clients');
        $this->delete("/admin/jobs/{$job->id}")->assertRedirect('/admin/jobs');

        $this->assertModelMissing($client);
        $this->assertModelMissing($job);
    }

    public function test_opening_a_message_marks_it_as_read(): void
    {
        $message = ContactMessage::factory()->create();

        $this->get("/admin/messages/{$message->id}")->assertOk()->assertSee($message->subject);
        $this->assertNotNull($message->fresh()->read_at);

        $this->patch("/admin/messages/{$message->id}/unread");
        $this->assertNull($message->fresh()->read_at);
    }

    public function test_admins_cannot_delete_their_own_account(): void
    {
        $this->delete("/admin/users/{$this->admin->id}")->assertSessionHasErrors('user');

        $this->assertModelExists($this->admin);
    }

    public function test_a_new_admin_user_can_be_created(): void
    {
        $this->post('/admin/users', [
            'name' => 'Colleague',
            'email' => 'colleague@example.com',
            'password' => 'password-123',
            'password_confirmation' => 'password-123',
        ])->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', ['email' => 'colleague@example.com']);
    }
}
