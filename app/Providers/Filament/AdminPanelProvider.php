<?php

namespace App\Providers\Filament;

use App\Enums\AdminNavigationGroup;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Widgets\HelpdeskOverview;
use App\Filament\Widgets\StatsOverview;
use App\Http\Middleware\SetUserLocale;
use App\Support\Settings;
use App\Support\TwoFactor;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->profile(EditProfile::class, isSimple: false)
            // Two-step verification is recommended, not required; one set-up works in /admin, /agent and the portal.
            ->multiFactorAuthentication(fn (): array => [TwoFactor::app(), TwoFactor::email()], isRequired: false)
            ->renderHook(PanelsRenderHook::CONTENT_START, fn () => view('filament.two-factor-reminder'))
            ->brandName(fn (): string => content('company.name').' · Admin')
            ->brandLogo(fn () => view('filament.brand'))
            ->brandLogoHeight('2.25rem')
            ->colors(fn (): array => [
                'primary' => Color::hex(Settings::color('appearance.accent', '#F7941D')),
                'gray' => Color::Slate,
            ])
            ->font('Inter')
            ->favicon(fn (): ?string => content_image('branding.favicon'))
            ->navigationGroups(AdminNavigationGroup::class)
            ->userMenuItems([
                Action::make('agentPanel')
                    ->label(fn (): string => __('helpdesk.panels.agent'))
                    ->icon(Heroicon::OutlinedLifebuoy)
                    ->url(fn (): string => url('/agent'))
                    ->visible(fn (): bool => (bool) auth()->user()?->isStaff()),
            ])
            ->navigationItems([
                NavigationItem::make('website')
                    ->label(fn (): string => __('admin.nav.view_site'))
                    ->url(fn (): string => page_url('home', in_array(app()->getLocale(), array_keys(config('site.locales')), true) ? app()->getLocale() : null), shouldOpenInNewTab: true)
                    ->icon(Heroicon::OutlinedGlobeAlt)
                    ->sort(100),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                AccountWidget::class,
                StatsOverview::class,
                HelpdeskOverview::class,
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
