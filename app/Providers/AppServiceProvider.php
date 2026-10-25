<?php

namespace App\Providers;

use App\Models\User;
use App\Support\HelpdeskSettings;
use App\Support\Settings;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(HelpdeskSettings::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Mail settings from the admin panel override .env once the mailer is first used.
        $this->app->afterResolving('mail.manager', fn () => Settings::applyMailConfig());

        // Password reset links for support portal clients; the admin and agent panels send their own links.
        ResetPassword::createUrlUsing(fn (User $user, string $token): string => support_route(
            'password.reset',
            ['token' => $token, 'email' => $user->getEmailForPasswordReset()],
            $user->preferredLocale(),
        ));
    }
}
