@php
    $companyName = content('company.name');
    $locales = config('site.locales');
    $page = current_page() ?? 'home';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title.' | '.$companyName : $companyName.' – '.content('company.tagline') }}</title>
    <meta name="description" content="{{ content('seo.description') }}">
    @foreach (array_keys($locales) as $locale)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ page_url($page, $locale) }}">
    @endforeach
    @if ($favicon = content_image('branding.favicon'))
        <link rel="icon" href="{{ $favicon }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
    <style>{!! \App\Support\Settings::themeCss() !!}</style>
</head>
<body class="page-{{ $page }}">
    @include('site.partials.header')

    <main>
        @yield('content')
    </main>

    @include('site.partials.footer')

    <script src="{{ asset('js/site.js') }}?v={{ filemtime(public_path('js/site.js')) }}" defer></script>
</body>
</html>
