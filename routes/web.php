<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\PageController;
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

Route::get('/', fn () => redirect()->route(config('site.default_locale').'.home'));

foreach (array_keys(config('site.locales')) as $locale) {
    Route::prefix($locale)
        ->name("{$locale}.")
        ->middleware(SetLocale::class.":{$locale}")
        ->group(function () use ($locale): void {
            foreach (config('site.pages') as $page => $slugs) {
                Route::get($slugs[$locale], [PageController::class, $page])->name($page);
            }

            Route::post(config("site.pages.contact.{$locale}"), ContactController::class)
                ->middleware('throttle:5,1')
                ->name('contact.send');
        });
}

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('login', [Admin\AuthController::class, 'create'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'store'])->middleware('throttle:10,1');
    });

    Route::middleware('auth')->group(function (): void {
        Route::post('logout', [Admin\AuthController::class, 'destroy'])->name('logout');

        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::get('content/{group}', [Admin\ContentController::class, 'edit'])->name('content.edit');
        Route::put('content/{group}', [Admin\ContentController::class, 'update'])->name('content.update');

        Route::resource('services', Admin\ServiceController::class)->except('show');
        Route::resource('projects', Admin\ProjectController::class)->except('show');
        Route::resource('clients', Admin\ClientController::class)->except('show');
        Route::resource('jobs', Admin\JobOfferController::class)->except('show')->parameters(['jobs' => 'jobOffer']);
        Route::resource('users', Admin\UserController::class)->except('show');

        Route::get('blocks/{group}', [Admin\ItemController::class, 'index'])->name('items.index');
        Route::get('blocks/{group}/create', [Admin\ItemController::class, 'create'])->name('items.create');
        Route::post('blocks/{group}', [Admin\ItemController::class, 'store'])->name('items.store');
        Route::get('items/{item}/edit', [Admin\ItemController::class, 'edit'])->name('items.edit');
        Route::put('items/{item}', [Admin\ItemController::class, 'update'])->name('items.update');
        Route::delete('items/{item}', [Admin\ItemController::class, 'destroy'])->name('items.destroy');

        Route::get('messages', [Admin\ContactMessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}', [Admin\ContactMessageController::class, 'show'])->name('messages.show');
        Route::patch('messages/{message}/unread', [Admin\ContactMessageController::class, 'markUnread'])->name('messages.unread');
        Route::delete('messages/{message}', [Admin\ContactMessageController::class, 'destroy'])->name('messages.destroy');
    });
});
