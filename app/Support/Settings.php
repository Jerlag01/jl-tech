<?php

namespace App\Support;

use App\Models\Content;
use Illuminate\Support\Facades\Crypt;
use Throwable;

/**
 * Website settings stored as content fields (config/content.php) that affect the
 * application itself: outgoing mail and the theme colours.
 */
class Settings
{
    /**
     * Override the mail configuration with the values set in the admin panel.
     * When no delivery method is chosen there, the .env configuration stays in effect.
     */
    public static function applyMailConfig(): void
    {
        try {
            $mailer = Content::text('mail.mailer');
        } catch (Throwable) {
            // No database yet (e.g. during installation): keep the .env configuration.
            return;
        }

        if (blank($mailer)) {
            return;
        }

        $password = Content::text('mail.password');

        config(array_filter([
            'mail.default' => $mailer,
            'mail.mailers.smtp.host' => Content::text('mail.host'),
            'mail.mailers.smtp.port' => Content::text('mail.port'),
            'mail.mailers.smtp.username' => Content::text('mail.username'),
            'mail.mailers.smtp.password' => filled($password) ? static::decrypt($password) : null,
            'mail.mailers.smtp.scheme' => Content::text('mail.scheme'),
            'mail.from.address' => Content::text('mail.from_address'),
            'mail.from.name' => Content::text('mail.from_name'),
        ], fn (mixed $value): bool => filled($value)));
    }

    /**
     * CSS custom properties that override the website's theme colours, plus the custom CSS.
     */
    public static function themeCss(): string
    {
        $primary = static::color('appearance.primary', '#002B45');
        $primaryDark = static::color('appearance.primary_dark', '#001823');
        $accent = static::color('appearance.accent', '#F7941D');
        $accentHover = static::color('appearance.accent_hover', '#E58413');

        $css = ":root{--primary:{$primary};--primary-dark:{$primaryDark};"
            ."--primary-light:color-mix(in srgb,{$primary} 80%,white);"
            ."--accent:{$accent};--accent-hover:{$accentHover};"
            ."--gradient-hero:linear-gradient(135deg,{$primaryDark} 0%,color-mix(in srgb,{$primary} 85%,white) 100%);"
            ."--gradient-accent:linear-gradient(135deg,{$accent} 0%,{$accentHover} 100%);}";

        // Custom CSS is written by admins only; stop it from closing the <style> element.
        return $css.str_ireplace('</style', '', Content::text('appearance.custom_css'));
    }

    /**
     * A colour setting when it is a valid hex colour, otherwise the fallback.
     */
    public static function color(string $key, string $fallback): string
    {
        $value = Content::text($key);

        return preg_match('/^#[0-9a-f]{6}$/i', $value) ? $value : $fallback;
    }

    protected static function decrypt(string $value): ?string
    {
        try {
            return Crypt::decryptString($value);
        } catch (Throwable) {
            return null;
        }
    }
}
