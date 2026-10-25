@php
    // Error pages render outside the localized route groups; derive the locale from the URL.
    $segment = request()->segment(1);
    app()->setLocale(array_key_exists($segment, config('site.locales')) ? $segment : config('site.default_locale'));
@endphp
@extends('layouts.site', ['title' => __('Page not found')])

@section('content')
    <section class="error-page">
        <div class="container">
            <div class="error-code">404</div>
            <h1>{{ __('Page not found') }}</h1>
            <p>{{ __('The page you are looking for does not exist or has been moved.') }}</p>
            <a href="{{ page_url('home') }}" class="btn btn-primary">{{ __('Back to the home page') }}</a>
        </div>
    </section>
@endsection
