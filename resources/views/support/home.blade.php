@extends('support.layout', ['title' => __('support.nav.tagline')])

@section('portal')
    <section class="portal-intro">
        <div class="page-header-grid"></div>
        <div class="container portal-intro-grid">
            <div>
                <span class="hero-badge portal-badge-static"><span class="dot"></span><span>{{ __('support.home.badge') }}</span></span>
                <h1>{{ __('support.home.title_start') }} <span class="accent">{{ __('support.home.title_highlight') }}</span></h1>
                <p>{{ __('support.home.intro', ['company' => helpdesk()->companyName()]) }}</p>
                <div class="hero-actions portal-intro-actions">
                    @auth
                        <a href="{{ support_route('tickets.create') }}" class="btn btn-primary btn-large">{{ __('support.nav.new_ticket') }} <x-site-icon name="arrow" size="16" /></a>
                        <a href="{{ support_route('tickets.index') }}" class="btn btn-outline-light btn-large">{{ __('support.nav.tickets') }}</a>
                    @else
                        @if (helpdesk()->get('allow_registration'))
                            <a href="{{ support_route('register') }}" class="btn btn-primary btn-large">{{ __('support.home.cta_register') }} <x-site-icon name="arrow" size="16" /></a>
                        @endif
                        <a href="{{ support_route('login') }}" class="btn btn-outline-light btn-large">{{ __('support.nav.login') }}</a>
                    @endauth
                </div>
            </div>
            <div class="hero-stats-card portal-steps">
                <div class="hero-stats-card-title">{{ __('support.home.how_title') }}</div>
                <ol>
                    @foreach (__('support.home.steps') as $step)
                        <li>
                            <span class="portal-step-number">{{ sprintf('%02d', $loop->iteration) }}</span>
                            <span><strong>{{ $step['title'] }}</strong><span>{{ $step['text'] }}</span></span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    @if ($topics->isNotEmpty())
        <section class="portal-section">
            <div class="container">
                <div class="reveal">
                    <span class="eyebrow">{{ __('support.home.categories_eyebrow') }}</span>
                    <h2 class="section-title">{{ __('support.home.categories_title') }}</h2>
                </div>
                <div class="portal-topics">
                    @foreach ($topics as $topic)
                        <a href="{{ auth()->check() ? support_route('tickets.create', ['topic' => $topic->id]) : support_route('login') }}" class="portal-topic reveal">
                            <span class="portal-mono">{{ sprintf('%02d', $loop->iteration) }}</span>
                            <x-site-icon :name="$topic->icon" size="32" class="portal-topic-icon" />
                            <h3>{{ $topic->label() }}</h3>
                            <p>{{ $topic->translate('description') }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($faqCategories->isNotEmpty())
        <section class="portal-section portal-section--muted">
            <div class="container">
                <div class="section-header-split">
                    <div class="reveal">
                        <span class="eyebrow">{{ __('support.kb.eyebrow') }}</span>
                        <h2 class="section-title">{{ __('support.kb.home_title') }}</h2>
                        <p class="intro-text">{{ __('support.kb.home_text') }}</p>
                    </div>
                    <a href="{{ support_route('kb.index') }}" class="btn btn-outline">{{ __('support.kb.browse') }} <x-site-icon name="arrow" size="16" /></a>
                </div>
                <div class="portal-kb-categories">
                    @foreach ($faqCategories as $category)
                        <a href="{{ support_route('kb.category', $category) }}" class="portal-card portal-kb-category reveal">
                            <span class="portal-icon-box"><x-site-icon name="book" size="20" /></span>
                            <span><strong>{{ $category->translate('name') }}</strong><span>{{ trans_choice('support.kb.articles', $category->faqs_count) }}</span></span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="portal-urgent">
        <div class="container portal-urgent-inner">
            <div>
                <h2>{{ __('support.home.urgent_title') }}</h2>
                <p>{{ __('support.home.urgent_text') }}</p>
            </div>
            @if ($phone = content('contact.phone'))
                <a href="tel:{{ preg_replace('/[^+\d]/', '', $phone) }}" class="btn btn-dark"><x-site-icon name="phone" size="16" /> {{ $phone }}</a>
            @else
                <a href="{{ auth()->check() ? support_route('tickets.create', ['priority' => 'urgent']) : support_route('login') }}" class="btn btn-dark">{{ __('support.home.urgent_button') }} <x-site-icon name="arrow" size="16" /></a>
            @endif
        </div>
    </section>
@endsection
