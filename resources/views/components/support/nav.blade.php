{{-- Second navigation bar inside the support portal. --}}
@php
    $links = array_filter([
        ['route' => 'home', 'label' => __('support.nav.home'), 'active' => ['support.home']],
        helpdesk()->get('show_knowledge_base') ? ['route' => 'kb.index', 'label' => __('support.nav.kb'), 'active' => ['support.kb.*']] : null,
        auth()->check() ? ['route' => 'tickets.index', 'label' => __('support.nav.tickets'), 'active' => ['support.tickets.index', 'support.tickets.show']] : null,
        auth()->check() ? ['route' => 'profile.edit', 'label' => __('support.nav.profile'), 'active' => ['support.profile.*', 'support.two-factor.*']] : null,
    ]);
    $routeName = (string) str((string) request()->route()?->getName())->after('.');
@endphp
<nav class="portal-nav" aria-label="{{ __('support.nav.main') }}">
    <div class="container portal-nav-inner">
        <div class="portal-nav-links">
            @foreach ($links as $link)
                <a href="{{ support_route($link['route']) }}" @class(['active' => str($routeName)->is($link['active'])])>{{ $link['label'] }}</a>
            @endforeach
        </div>
        <div class="portal-nav-actions">
            @auth
                @if (auth()->user()->isStaff())
                    <a href="{{ url('/agent') }}" class="portal-nav-link">{{ __('support.nav.agent_panel') }}</a>
                @endif
                <a href="{{ support_route('tickets.create') }}" class="btn btn-primary btn-small"><x-site-icon name="plus" size="16" /> {{ __('support.nav.new_ticket') }}</a>
                <form method="POST" action="{{ support_route('logout') }}">
                    @csrf
                    <button type="submit" class="portal-icon-button" title="{{ __('support.nav.logout') }}"><x-site-icon name="logout" size="18" /><span class="sr-only">{{ __('support.nav.logout') }}</span></button>
                </form>
            @else
                <a href="{{ support_route('login') }}" class="portal-nav-link">{{ __('support.nav.login') }}</a>
                @if (helpdesk()->get('allow_registration'))
                    <a href="{{ support_route('register') }}" class="btn btn-primary btn-small">{{ __('support.nav.register') }}</a>
                @endif
            @endauth
        </div>
    </div>
</nav>
