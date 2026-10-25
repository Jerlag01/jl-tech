<?php

namespace Tests\Feature;

use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Models\BlockedIp;
use App\Models\Client;
use App\Models\ContactMessage;
use App\Models\Employee;
use App\Models\FooterLink;
use App\Models\Item;
use App\Models\JobOffer;
use App\Models\Post;
use App\Models\Project;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RolePermissionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{class-string, string, string}>
     */
    public static function areas(): array
    {
        return [
            'expertises' => [Service::class, 'services', '/admin/expertises'],
            'projects' => [Project::class, 'projects', '/admin/realisaties'],
            'news' => [Post::class, 'posts', '/admin/posts'],
            'clients' => [Client::class, 'clients', '/admin/clients'],
            'who is who' => [Employee::class, 'employees', '/admin/who-is-who'],
            'vacancies' => [JobOffer::class, 'job_offers', '/admin/vacancies'],
            'page blocks' => [Item::class, 'page_blocks', '/admin/page-blocks'],
            'footer links' => [FooterLink::class, 'footer_links', '/admin/footer-links'],
            'contact messages' => [ContactMessage::class, 'contact_messages', '/admin/contact-messages'],
            'blocked ips' => [BlockedIp::class, 'blocked_ips', '/admin/blocked-ips'],
            'users' => [User::class, 'users', '/admin/users'],
            'roles' => [Role::class, 'roles', '/admin/roles'],
        ];
    }

    /**
     * @param  class-string<Model>  $model
     */
    #[DataProvider('areas')]
    public function test_each_area_is_only_reachable_with_its_own_permission(string $model, string $area, string $url): void
    {
        $record = $model::factory()->create();

        $none = User::factory()->withPermissions([])->create();
        $viewer = User::factory()->withPermissions([$area => ['view']])->create();

        $this->assertFalse($none->can('viewAny', $model));
        $this->assertTrue($viewer->can('viewAny', $model));
        $this->assertFalse($viewer->can('update', $record));
        $this->assertFalse($viewer->can('delete', $record));

        $this->actingAs($none)->get($url)->assertForbidden();
        $this->actingAs($viewer)->get($url)->assertOk();
    }

    public function test_a_role_only_sees_the_navigation_it_has_permission_for(): void
    {
        $this->actingAs(User::factory()->withPermissions(['posts' => ['view']])->create())
            ->get('/admin')
            ->assertOk()
            ->assertSee('/admin/posts', escape: false)
            ->assertDontSee('/admin/realisaties', escape: false)
            ->assertDontSee('/admin/settings', escape: false);
    }

    public function test_view_only_users_cannot_open_create_or_edit_pages(): void
    {
        $post = Post::factory()->create();
        $this->actingAs(User::factory()->withPermissions(['posts' => ['view']])->create());

        $this->get('/admin/posts/create')->assertForbidden();
        $this->get("/admin/posts/{$post->getKey()}/edit")->assertForbidden();
    }

    public function test_roles_are_created_with_permissions_and_unknown_ones_are_dropped(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(CreateRole::class)
            ->fillForm([
                'name' => 'Webredactie',
                'permissions' => ['posts' => ['view', 'create']],
            ])
            ->set('data.permissions.unknown', ['view'])
            ->call('create')
            ->assertHasNoFormErrors();

        $role = Role::query()->firstWhere('name', 'Webredactie');
        $this->assertSame(['posts' => ['view', 'create']], $role->permissions);
        $this->assertTrue($role->allows('posts.create'));
        $this->assertFalse($role->allows('posts.delete'));
    }

    public function test_existing_users_became_administrators_and_default_roles_exist(): void
    {
        $this->assertTrue(Role::query()->where('name', 'Administrator')->where('is_super', true)->exists());
        $this->assertTrue(Role::query()->firstWhere('name', 'Editor')->allows('posts.update'));
    }
}
