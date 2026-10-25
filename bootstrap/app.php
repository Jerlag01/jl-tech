<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // The admin and agent panels have their own login pages; everything else is the support portal.
        // Authentication runs before the locale middleware, so take the language from the URL.
        $locale = fn (Request $request): ?string => array_key_exists((string) $request->segment(1), config('site.locales')) ? $request->segment(1) : null;
        $middleware->redirectGuestsTo(fn (Request $request) => support_route('login', [], $locale($request)));
        $middleware->redirectUsersTo(fn (Request $request) => support_route('tickets.index', [], $locale($request)));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
