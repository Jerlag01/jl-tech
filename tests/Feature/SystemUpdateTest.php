<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\Updater;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Keep the page from asking the real git remote.
        cache()->put('updater.remote.'.md5(base_path()), ['hash' => null, 'checked_at' => now()->toIso8601String(), 'error' => 'offline'], 600);
        cache()->put('updater.ownership.'.md5(base_path()), [], 600);
    }

    public function test_only_super_administrators_can_open_the_update_page(): void
    {
        $this->actingAs(User::factory()->admin()->create(['locale' => 'en']))->get('/admin/system-update')->assertOk()->assertSee('System update');
        $this->actingAs(User::factory()->withPermissions(['settings' => ['general', 'contact', 'mail', 'appearance']])->create())->get('/admin/system-update')->assertForbidden();
    }

    public function test_an_update_request_is_only_run_by_the_scheduler_when_requested(): void
    {
        $updater = new Updater(stateDirectory: storage_path('framework/testing/updater'));
        $updater->cancelRequest();

        $this->assertNull($updater->pendingRequest());

        $updater->requestUpdate(['user' => 'Test']);
        $this->assertSame('Test', $updater->pendingRequest()['user']);

        $updater->cancelRequest();
        $this->assertNull($updater->pendingRequest());
    }
}
