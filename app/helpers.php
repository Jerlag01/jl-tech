<?php

use App\Models\Content;
use App\Models\Employee;
use App\Models\Post;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

if (! function_exists('content')) {
    /**
     * An editable text or setting from config/content.php, in the current locale.
     */
    function content(string $key): string
    {
        return Content::text($key);
    }
}

if (! function_exists('content_image')) {
    /**
     * Public URL of an editable image field, or null when no image is uploaded.
     */
    function content_image(string $key): ?string
    {
        return media_url(Content::text($key));
    }
}

if (! function_exists('key_figure')) {
    /**
     * A key figure (e.g. "stats.employees"), or null when it has no value so the view can hide it.
     */
    function key_figure(string $key): ?string
    {
        $value = trim(content($key));

        return $value === '' || $value === '0' ? null : $value;
    }
}

if (! function_exists('content_enabled')) {
    function content_enabled(string $key): bool
    {
        return (bool) Content::raw($key);
    }
}

if (! function_exists('media_url')) {
    /**
     * Public URL for a file stored on the public disk.
     */
    function media_url(?string $path): ?string
    {
        return filled($path) ? Storage::disk('public')->url($path) : null;
    }
}

if (! function_exists('page_enabled')) {
    function page_enabled(string $page): bool
    {
        return match (true) {
            $page === 'who_is_who' => Employee::pageIsAvailable(),
            $page === 'news' => Post::pageIsAvailable(),
            in_array($page, config('site.optional_pages'), true) => content_enabled("pages.{$page}"),
            default => true,
        };
    }
}

if (! function_exists('page_url')) {
    /**
     * URL of a public page in the given (or current) locale.
     */
    function page_url(string $page, ?string $locale = null, string $fragment = ''): string
    {
        $locale ??= app()->getLocale();
        $name = "{$locale}.{$page}";

        // Pages with parameters (a news post) reuse the current ones, e.g. for the language switcher.
        $url = Route::has($name) ? route($name, $page === current_page() ? (Route::current()?->parameters() ?? []) : []) : url($locale);

        return $fragment !== '' ? "{$url}#{$fragment}" : $url;
    }
}

if (! function_exists('current_page')) {
    /**
     * The page key of the current public route, e.g. "services" for "nl.services".
     */
    function current_page(): ?string
    {
        $name = Route::currentRouteName();

        if ($name === null || ! str_contains($name, '.')) {
            return null;
        }

        [$locale, $page] = explode('.', $name, 2);

        return array_key_exists($locale, config('site.locales')) ? $page : null;
    }
}
