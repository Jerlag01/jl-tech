<?php

namespace Tests\Feature;

use App\Filament\Agent\Resources\Clients\Pages\ListClients;
use App\Models\User;
use App\Support\TwoFactor;
use Filament\Auth\MultiFactor\Email\Notifications\VerifyEmailAuthentication;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use PragmaRX\Google2FAQRCode\Google2FA;
use Tests\TestCase;

class SupportTwoFactorTest extends TestCase
{
    use RefreshDatabase;

    protected function userWithApp(): array
    {
        $user = User::factory()->customer()->create(['password' => 'password-123']);
        $secret = TwoFactor::app()->generateSecret();
        $user->saveAppAuthenticationSecret($secret);
        TwoFactor::app()->saveRecoveryCodes($user, ['first-recovery-code', 'second-recovery-code']);

        return [$user, $secret];
    }

    protected function currentCode(string $secret): string
    {
        return app(Google2FA::class)->getCurrentOtp($secret);
    }

    public function test_login_without_two_step_verification_is_unchanged(): void
    {
        $user = User::factory()->customer()->create(['password' => 'password-123']);

        $this->post('/nl/support/login', ['email' => $user->email, 'password' => 'password-123'])->assertRedirect(support_route('tickets.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_the_authenticator_app_is_set_up_from_the_profile(): void
    {
        $user = User::factory()->customer()->create();
        $this->actingAs($user);

        $this->get('/nl/support/profile')->assertOk()->assertSee('data-two-factor-reminder', escape: false);
        $this->get('/nl/support/profile/two-factor/app')->assertOk()->assertSee('data-secret', escape: false)->assertSee('data:image/', escape: false);

        $secret = session('two_factor_setup.app_secret');
        $this->post('/nl/support/profile/two-factor/app', ['code' => '000000'])->assertSessionHasErrors('code');

        $this->post('/nl/support/profile/two-factor/app', ['code' => $this->currentCode($secret)])
            ->assertRedirect(support_route('profile.edit'))
            ->assertSessionHas('recovery_codes');

        $user->refresh();
        $this->assertSame($secret, $user->app_authentication_secret);
        $this->assertCount(8, $user->app_authentication_recovery_codes);
        $this->assertTrue($user->hasTwoFactor());

        $this->get('/nl/support/profile')->assertDontSee('data-two-factor-reminder', escape: false);
    }

    public function test_login_with_the_app_asks_for_the_code_before_logging_in(): void
    {
        [$user, $secret] = $this->userWithApp();

        $this->post('/nl/support/login', ['email' => $user->email, 'password' => 'password-123'])->assertRedirect(support_route('two-factor.challenge'));
        $this->assertGuest();

        $this->get('/nl/support/two-factor')->assertOk()->assertSee(__('support.two_factor.challenge_app'));
        $this->post('/nl/support/two-factor', ['method' => 'app', 'code' => '123456'])->assertSessionHasErrors('code');
        $this->assertGuest();

        $this->post('/nl/support/two-factor', ['method' => 'app', 'code' => $this->currentCode($secret)])->assertRedirect(support_route('tickets.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_a_recovery_code_works_once(): void
    {
        [$user] = $this->userWithApp();

        $this->post('/nl/support/login', ['email' => $user->email, 'password' => 'password-123']);
        $this->post('/nl/support/two-factor', ['method' => 'recovery', 'code' => 'first-recovery-code'])->assertRedirect(support_route('tickets.index'));
        $this->assertAuthenticatedAs($user);
        $this->assertCount(1, $user->fresh()->app_authentication_recovery_codes);

        $this->post('/nl/support/logout');
        $this->post('/nl/support/login', ['email' => $user->email, 'password' => 'password-123']);
        $this->post('/nl/support/two-factor', ['method' => 'recovery', 'code' => 'first-recovery-code'])->assertSessionHasErrors('code');
        $this->assertGuest();
    }

    public function test_codes_by_email_are_set_up_and_used_at_login(): void
    {
        Notification::fake();
        $user = User::factory()->customer()->create(['password' => 'password-123']);
        $this->actingAs($user);

        // Filament ties e-mail codes to the session; keep one session cookie like a browser does.
        $this->withCookie(config('session.cookie'), str_repeat('a', 40));

        $this->post('/nl/support/profile/two-factor/email/send')->assertRedirect(support_route('two-factor.email.create'));
        $code = null;
        Notification::assertSentTo($user, VerifyEmailAuthentication::class, function (VerifyEmailAuthentication $notification) use (&$code): bool {
            $code = $notification->code;

            return true;
        });

        $this->post('/nl/support/profile/two-factor/email', ['code' => $code])->assertRedirect(support_route('profile.edit'));
        $this->assertTrue($user->fresh()->has_email_authentication);

        $this->post('/nl/support/logout');
        Notification::fake();

        $this->post('/nl/support/login', ['email' => $user->email, 'password' => 'password-123'])->assertRedirect(support_route('two-factor.challenge'));
        $this->get('/nl/support/two-factor')->assertOk()->assertSee(__('support.two_factor.challenge_email'));

        Notification::assertSentTo($user, VerifyEmailAuthentication::class, function (VerifyEmailAuthentication $notification) use (&$code): bool {
            $code = $notification->code;

            return true;
        });

        $this->post('/nl/support/two-factor', ['method' => 'email', 'code' => $code])->assertRedirect(support_route('tickets.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_switching_a_method_off_needs_the_password(): void
    {
        [$user] = $this->userWithApp();
        $user->toggleEmailAuthentication(true);
        $this->actingAs($user);

        $this->delete('/nl/support/profile/two-factor/app', ['app_password' => 'wrong'])->assertSessionHasErrorsIn('twoFactorApp', 'app_password');
        $this->assertNotNull($user->fresh()->app_authentication_secret);

        $this->delete('/nl/support/profile/two-factor/app', ['app_password' => 'password-123'])->assertRedirect(support_route('profile.edit'));
        $this->delete('/nl/support/profile/two-factor/email', ['email_password' => 'password-123'])->assertRedirect(support_route('profile.edit'));

        $this->assertFalse($user->fresh()->hasTwoFactor());
    }

    public function test_new_recovery_codes_replace_the_old_ones(): void
    {
        [$user] = $this->userWithApp();
        $this->actingAs($user);

        $this->post('/nl/support/profile/two-factor/recovery-codes', ['recovery_password' => 'password-123'])->assertSessionHas('recovery_codes');

        $this->assertCount(8, $user->fresh()->app_authentication_recovery_codes);
        $this->assertFalse(TwoFactor::app()->verifyRecoveryCode('first-recovery-code', $user->fresh()));
    }

    public function test_a_pending_login_expires(): void
    {
        [$user, $secret] = $this->userWithApp();

        $this->post('/nl/support/login', ['email' => $user->email, 'password' => 'password-123']);
        $this->travel(TwoFactor::PENDING_MINUTES + 1)->minutes();

        $this->post('/nl/support/two-factor', ['method' => 'app', 'code' => $this->currentCode($secret)])->assertRedirect(support_route('login'));
        $this->assertGuest();
        $this->get('/nl/support/two-factor')->assertRedirect(support_route('login'));
    }

    public function test_the_challenge_page_needs_a_password_first(): void
    {
        $this->get('/nl/support/two-factor')->assertRedirect(support_route('login'));
        $this->post('/nl/support/two-factor', ['method' => 'app', 'code' => '123456'])->assertRedirect(support_route('login'));
    }

    public function test_the_panels_offer_both_methods_without_requiring_them(): void
    {
        foreach (['agent', 'admin'] as $panelId) {
            $panel = Filament::getPanel($panelId);
            Filament::setCurrentPanel($panel);

            $this->assertTrue($panel->hasMultiFactorAuthentication());
            $this->assertFalse($panel->isMultiFactorAuthenticationRequired());
            $this->assertEqualsCanonicalizing(['app', 'email_code'], array_keys($panel->getMultiFactorAuthenticationProviders()));
        }
    }

    public function test_the_panel_dashboard_recommends_it_until_it_is_set_up(): void
    {
        $agent = User::factory()->agent()->create();

        $this->actingAs($agent)->get('/agent')->assertOk()->assertSee('data-two-factor-reminder', escape: false);
        $this->actingAs($agent)->get('/agent/profile')->assertOk();

        $agent->toggleEmailAuthentication(true);
        $this->actingAs($agent->fresh())->get('/agent')->assertOk()->assertDontSee('data-two-factor-reminder', escape: false);
    }

    public function test_an_admin_can_reset_it_for_someone_who_lost_their_phone(): void
    {
        [$client] = $this->userWithApp();
        $client->toggleEmailAuthentication(true);
        $agent = User::factory()->agent()->create();

        $this->actingAs($agent);
        Filament::setCurrentPanel('agent');

        Livewire::test(ListClients::class)
            ->assertTableActionVisible('resetTwoFactor', $client)
            ->callTableAction('resetTwoFactor', $client)
            ->assertHasNoTableActionErrors();

        $this->assertFalse($client->fresh()->hasTwoFactor());
        $this->assertNull($client->fresh()->app_authentication_recovery_codes);
    }
}
