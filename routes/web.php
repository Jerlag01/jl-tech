<?php

use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\PageController;
use App\Http\Middleware\BlockBlockedIps;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public website
|--------------------------------------------------------------------------
|
| One route set per locale with translated slugs (config/site.php), named
| "{locale}.{page}", e.g. route('nl.services') → /nl/expertises.
|
*/

Route::get('/', fn () => redirect()->route(config('site.default_locale').'.home'))->middleware(BlockBlockedIps::class);

foreach (array_keys(config('site.locales')) as $locale) {
    Route::prefix($locale)
        ->name("{$locale}.")
        ->middleware([BlockBlockedIps::class, SetLocale::class.":{$locale}"])
        ->group(function () use ($locale): void {
            foreach (config('site.pages') as $page => $slugs) {
                Route::get($slugs[$locale], [PageController::class, str($page)->camel()->toString()])->name($page);
            }

            Route::get(config("site.pages.news.{$locale}").'/{post:slug}', [PageController::class, 'post'])->name('news.show');

            Route::post(config("site.pages.contact.{$locale}"), ContactController::class)
                ->middleware('throttle:5,1')
                ->name('contact.send');
        });
}
