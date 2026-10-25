<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>410 · {{ __('Access denied') }} | {{ content('company.name') }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=JetBrains+Mono:wght@600&display=swap">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    <style>{!! \App\Support\Settings::themeCss() !!}</style>
</head>
<body class="blocked-body">
    <main class="blocked-card">
        <div class="logo-text">{{ content('company.name') }}</div>
        <p class="blocked-code">410</p>
        <h1>{{ __('Access denied') }}</h1>
        <p>{{ __('Access to this website from your network has been blocked. If you think this is a mistake, please contact us by phone or email.') }}</p>
    </main>
</body>
</html>
