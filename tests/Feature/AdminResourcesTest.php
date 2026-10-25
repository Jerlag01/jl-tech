<?php

namespace Tests\Feature;

use App\Filament\Resources\ContactMessages\Pages\ViewContactMessage;
use App\Filament\Resources\PageBlocks\Pages\CreatePageBlock;
use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Services\Pages\CreateService;
use App\Models\ContactMessage;
use App\Models\Item;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminResourcesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->admin()->create());
    }

    public function test_an_expertise_can_be_created_with_key_figures(): void
    {
        Livewire::test(CreateService::class)
            ->fillForm([
                'title' => ['nl' => 'Glasvezel', 'en' => 'Fibre'],
                'features' => ['nl' => "Eén\nTwee", 'en' => ''],
                'stats' => [['value' => '50+', 'label' => ['nl' => 'Projecten', 'en' => 'Projects']]],
                'slug' => 'glasvezel',
                'icon' => 'fiber',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $service = Service::query()->sole();
        $this->assertSame(['Eén', 'Twee'], $service->trLines('features', 'nl'));
        $this->assertSame([['value' => '50+', 'label' => 'Projects']], $service->trStats('stats', 'en'));
        $this->get('/nl/expertises')->assertSee('Glasvezel');
    }

    public function test_only_one_project_can_be_featured(): void
    {
        $first = Project::factory()->featured()->create();

        Livewire::test(CreateProject::class)
            ->fillForm(['title' => ['nl' => 'Nieuw', 'en' => ''], 'featured' => true, 'active' => true])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertFalse($first->fresh()->featured);
        $this->assertTrue(Project::query()->latest('id')->first()->featured);
    }

    public function test_editing_a_project_keeps_its_translations(): void
    {
        $project = Project::factory()->create(['title' => ['nl' => 'Oud', 'en' => 'Old']]);

        Livewire::test(EditProject::class, ['record' => $project->getKey()])
            ->fillForm(['title.nl' => 'Nieuw'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame(['nl' => 'Nieuw', 'en' => 'Old'], $project->fresh()->title);
    }

    public function test_page_blocks_are_created_in_the_section_from_the_link(): void
    {
        $this->get('/admin/page-blocks/create?group=timeline')->assertOk();

        Livewire::withQueryParams(['group' => 'timeline'])
            ->test(CreatePageBlock::class)
            ->assertSet('data.group', 'timeline')
            ->fillForm(['value' => '2020', 'title' => ['nl' => 'Mijlpaal', 'en' => 'Milestone']])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('items', ['group' => 'timeline', 'value' => '2020']);
        $this->get('/en/history')->assertSee('Milestone');
    }

    public function test_page_block_tabs_render(): void
    {
        Item::factory()->create(['group' => 'fleet']);

        $this->get('/admin/page-blocks')->assertOk()->assertSee('Team – Fleet &amp; equipment', false);
    }

    public function test_opening_a_message_marks_it_as_read(): void
    {
        $message = ContactMessage::factory()->create();

        Livewire::test(ViewContactMessage::class, ['record' => $message->getKey()])->assertOk();

        $this->assertNotNull($message->fresh()->read_at);
    }
}
