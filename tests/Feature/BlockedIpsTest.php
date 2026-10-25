<?php

namespace Tests\Feature;

use App\Filament\Resources\BlockedIps\Pages\CreateBlockedIp;
use App\Models\BlockedIp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BlockedIpsTest extends TestCase
{
    use RefreshDatabase;

    public function test_blocked_visitors_get_the_access_denied_page_but_the_admin_stays_reachable(): void
    {
        BlockedIp::factory()->create(['ip' => '203.0.113.0/24']);

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.7'])->get('/nl')->assertStatus(410)->assertSee('Toegang geweigerd');
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.7'])->get('/admin/login')->assertOk();
        $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.1'])->get('/nl')->assertOk();

        $this->assertSame(1, BlockedIp::query()->sole()->hits);
    }

    public function test_expired_and_inactive_blocks_do_not_block(): void
    {
        BlockedIp::factory()->expired()->create(['ip' => '203.0.113.7']);
        BlockedIp::factory()->inactive()->create(['ip' => '203.0.113.8']);

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.7'])->get('/en')->assertOk();
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.8'])->get('/en')->assertOk();
    }

    public function test_ranges_that_are_too_wide_are_refused(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(CreateBlockedIp::class)
            ->fillForm(['ip' => '10.0.0.0/8'])
            ->call('create')
            ->assertHasFormErrors(['ip']);

        Livewire::test(CreateBlockedIp::class)
            ->fillForm(['ip' => '203.0.113.9'])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('blocked_ips', ['ip' => '203.0.113.9']);
    }

    public function test_the_contact_form_stores_the_sender_ip(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.4'])->post('/en/contact', [
            'name' => 'Jan', 'email' => 'jan@example.com', 'subject' => 'Offerte', 'message' => 'Hallo', 'privacy' => '1',
        ]);

        $this->assertDatabaseHas('contact_messages', ['ip' => '198.51.100.4']);
    }
}
