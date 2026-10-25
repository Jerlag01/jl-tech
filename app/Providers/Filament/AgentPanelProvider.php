<?php

namespace App\Providers\Filament;

use App\Enums\AgentNavigationGroup;
use App\Http\Middleware\SetUserLocale;
use App\Support\Settings;
use App\Support\TwoFactor;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * Day-to-day ticket work for helpdesk agents (formerly the support-app's agent panel).
 */
class AgentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('agent')
            ->path('agent')
            ->login()
            ->passwordReset()
            ->profile(isSimple: false)
            // Recommended, not required: users switch it on in their profile.
            ->multiFactorAuthentication(fn (): array => [TwoFactor::app(), TwoFactor::email()], isRequired: false)
            ->renderHook(PanelsRenderHook::CONTENT_START, fn () => view('filament.two-factor-reminder'))
            ->brandName(fn (): string => content('company.name').' · Helpdesk')
            ->brandLogo(fn () => view('filament.brand', ['label' => 'Helpdesk']))
            ->brandLogoHeight('2.25rem')
            ->colors(fn (): array => [
                'primary' => Color::hex(Settings::color('appearance.accent', '#F7941D')),
                'gray' => Color::Slate,
            ])
            ->font('Inter')
            ->favicon(fn (): ?string => content_image('branding.favicon'))
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups(AgentNavigationGroup::navigationGroups([
                AgentNavigationGroup::Tickets,
                AgentNavigationGroup::Users,
                AgentNavigationGroup::KnowledgeBase,
            ]))
            ->userMenuItems([
                Action::make('adminPanel')
                    ->label(fn (): string => __('helpdesk.panels.admin'))
                    ->icon(Heroicon::OutlinedCog6Tooth)
                    ->url(fn (): string => url('/admin'))
                    ->visible(fn (): bool => auth()->user()?->role_id !== null),
                Action::make('clientPortal')
                    ->label(fn (): string => __('helpdesk.panels.portal'))
                    ->icon(Heroicon::OutlinedGlobeAlt)
                    ->url(fn (): string => support_route('home'), shouldOpenInNewTab: true),
            ])
            ->discoverResources(in: app_path('Filament/Agent/Resources'), for: 'App\Filament\Agent\Resources')
            ->discoverPages(in: app_path('Filament/Agent/Pages'), for: 'App\Filament\Agent\Pages')
            ->discoverWidgets(in: app_path('Filament/Agent/Widgets'), for: 'App\Filament\Agent\Widgets')
            ->pages([
                Dashboard::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                SetUserLocale::class,
            ], isPersistent: true);
    }
}
