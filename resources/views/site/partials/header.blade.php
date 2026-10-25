@php
    $navServices = \App\Models\Service::query()->visible()->get(['id', 'slug', 'title']);
    $companyPages = collect(['history', 'team', 'who_is_who', 'esg'])->filter(fn ($p) => page_enabled($p));
    $current = current_page();
@endphp
<nav class="navbar" id="navbar">
    <div class="container navbar-inner">
        <a href="{{ page_url('home') }}" class="nav-brand">
            @include('site.partials.logo')
        </a>

        <ul class="nav-menu" id="navMenu">
            <li><a href="{{ page_url('home') }}" @class(['active' => $current === 'home'])>{{ __('Home') }}</a></li>
            @if ($navServices->isNotEmpty())
                <li class="nav-item-dropdown">
                    <a href="{{ page_url('services') }}" class="nav-trigger {{ $current === 'services' ? 'active' : '' }}">
                        {{ __('Services') }} <x-site-icon name="chevron" size="12" class="chevron" />
                    </a>
                    <ul class="nav-dropdown">
                        @foreach ($navServices as $service)
                            <li><a href="{{ page_url('services', null, $service->slug) }}">{{ $service->tr('title') }}</a></li>
                        @endforeach
                    </ul>
                </li>
            @endif
            <li><a href="{{ page_url('projects') }}" @class(['active' => $current === 'projects'])>{{ __('Projects') }}</a></li>
            @if ($companyPages->isNotEmpty())
                <li class="nav-item-dropdown">
                    <a href="{{ page_url($companyPages->first()) }}" class="nav-trigger {{ $companyPages->contains($current) ? 'active' : '' }}">
                        {{ __('Company') }} <x-site-icon name="chevron" size="12" class="chevron" />
                    </a>
                    <ul class="nav-dropdown">
                        @foreach ($companyPages as $companyPage)
                            <li><a href="{{ page_url($companyPage) }}" @class(['active' => $current === $companyPage])>{{ __('page.'.$companyPage) }}</a></li>
                        @endforeach
                    </ul>
                </li>
            @endif
            @if (page_enabled('news'))
                <li><a href="{{ page_url('news') }}" @class(['active' => in_array($current, ['news', 'news.show'], true)])>{{ __('News') }}</a></li>
            @endif
            @if (page_enabled('jobs'))
                <li><a href="{{ page_url('jobs') }}" @class(['active' => $current === 'jobs'])>{{ __('Careers') }}</a></li>
            @endif
            <li><a href="{{ support_route('home') }}" @class(['active' => str((string) $current)->startsWith('support.')])>{{ __('Support') }}</a></li>
            <li><a href="{{ page_url('contact') }}" class="btn-nav {{ $current === 'contact' ? 'active' : '' }}">{{ __('Contact') }}</a></li>
            <li class="lang-switch" aria-label="{{ __('Language') }}">
                @foreach (config('site.locales') as $locale => $label)
                    <a href="{{ page_url($current ?? 'home', $locale) }}" hreflang="{{ $locale }}" title="{{ $label }}" @class(['active' => app()->getLocale() === $locale])>{{ strtoupper($locale) }}</a>
                @endforeach
            </li>
        </ul>

        <button type="button" class="hamburger" id="hamburger" aria-label="Menu" aria-controls="navMenu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>
