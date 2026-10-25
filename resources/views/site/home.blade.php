@extends('layouts.site')

@section('content')
    @php($heroImage = content_image('home.hero_image'))

    {{-- Hero --}}
    <section class="hero">
        <div class="hero-bg" id="heroBg" @if ($heroImage) style="background-image: url('{{ $heroImage }}')" @endif></div>
        <div class="hero-overlay"></div>
        <div class="hero-grid"></div>
        <div class="hero-accent-line hero-accent-line--vertical"></div>
        <div class="hero-accent-line hero-accent-line--horizontal"></div>
        <div class="hero-accent-dot hero-accent-dot--top"></div>
        <div class="hero-accent-dot hero-accent-dot--bottom"></div>

        <div class="hero-content">
            <div class="hero-inner">
                <div class="hero-text">
                    @if (content('home.hero_badge'))
                        <div class="hero-badge"><span class="dot"></span><span>{{ content('home.hero_badge') }}</span></div>
                    @endif
                    <h1 class="hero-title">
                        <span class="line"><span>{{ content('home.hero_title_1') }}</span></span>
                        <span class="line"><span class="accent">{{ content('home.hero_title_2') }}</span></span>
                        <span class="line"><span>{{ content('home.hero_title_3') }}</span></span>
                    </h1>
                    <p class="hero-description">{{ content('home.hero_text') }}</p>
                    <div class="hero-actions">
                        <a href="{{ page_url('projects') }}" class="btn btn-primary btn-large">{{ content('home.hero_button_1') }} <x-icon name="arrow" size="16" /></a>
                        <a href="{{ page_url('services') }}" class="btn btn-outline-light btn-large">{{ content('home.hero_button_2') }}</a>
                    </div>
                </div>

                <div class="hero-visual">
                    <div class="hero-stats-card">
                        <div class="hero-stats-card-title">{{ content('home.hero_card_title') }}</div>
                        <div class="hero-stat-row">
                            <span class="hero-stat-number">±{{ content('stats.employees') }}</span>
                            <span class="hero-stat-label">{{ content('home.fig_employees_label') }}</span>
                        </div>
                        <div class="hero-stat-row">
                            <span class="hero-stat-number">{{ content('stats.vehicles') }}+</span>
                            <span class="hero-stat-label">{{ content('home.fig_vehicles_label') }}</span>
                        </div>
                        <div class="hero-stat-row">
                            <span class="hero-stat-number">{{ content('stats.projects') }}+</span>
                            <span class="hero-stat-label">{{ content('home.fig_projects_label') }}</span>
                        </div>
                        <div class="hero-stat-row">
                            <span class="hero-stat-number">{{ content('company.founded_year') }}</span>
                            <span class="hero-stat-label">{{ content('home.fig_founded_label') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="#after-hero" class="hero-scroll"><span class="hero-scroll-text">{{ __('Scroll') }}</span><span class="hero-scroll-line"></span></a>
    </section>

    <div id="after-hero"></div>

    {{-- Client marquee --}}
    @if ($marqueeClients->isNotEmpty())
        <section class="marquee-section" aria-hidden="true">
            <div class="marquee-track">
                {{-- Repeated so the loop is seamless at -50% --}}
                @for ($i = 0; $i < 4; $i++)
                    @foreach ($marqueeClients as $client)
                        <div class="marquee-item">{{ $client->name }} <span class="separator"></span></div>
                    @endforeach
                @endfor
            </div>
        </section>
    @endif

    {{-- About --}}
    <section class="intro-section">
        <div class="container">
            <div class="intro-grid">
                <div class="reveal-left">
                    <span class="eyebrow">{{ content('home.intro_label') }}</span>
                    <h2 class="section-title">{{ content('home.intro_title') }}</h2>
                    <p class="intro-text">{{ content('home.intro_text') }}</p>
                    @if ($services->isNotEmpty())
                        <ul class="intro-feature-list">
                            @foreach ($services as $service)
                                <li><x-icon name="check" size="18" /> {{ $service->tr('title') }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="intro-visual reveal-right">
                    <div class="intro-image-main">
                        @include('site.partials.image', ['src' => content_image('home.intro_image'), 'alt' => content('home.intro_title'), 'icon' => 'hardhat'])
                    </div>
                    @if (content('home.intro_badge_number'))
                        <div class="intro-image-badge">
                            <span class="number">{{ content('home.intro_badge_number') }}</span>
                            <span class="label">{{ content('home.intro_badge_label') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Key figures --}}
    <section class="figures">
        <div class="container">
            <div class="figures-grid">
                @foreach ([
                    ['count' => content('stats.employees'), 'prefix' => '±', 'suffix' => '', 'label' => 'employees'],
                    ['count' => content('stats.vehicles'), 'prefix' => '', 'suffix' => '+', 'label' => 'vehicles'],
                    ['count' => content('company.founded_year'), 'prefix' => '', 'suffix' => '', 'label' => 'founded'],
                    ['count' => content('stats.projects'), 'prefix' => '', 'suffix' => '+', 'label' => 'projects'],
                ] as $figure)
                    <div class="figures-item reveal reveal-delay-{{ $loop->iteration }}">
                        <span class="figures-number" data-count="{{ (int) $figure['count'] }}" data-prefix="{{ $figure['prefix'] }}" data-suffix="{{ $figure['suffix'] }}">{{ $figure['prefix'] }}{{ $figure['count'] }}{{ $figure['suffix'] }}</span>
                        <span class="figures-label">{{ content('home.fig_'.$figure['label'].'_label') }}</span>
                        <span class="figures-sub">{{ content('home.fig_'.$figure['label'].'_sub') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Services --}}
    @if ($services->isNotEmpty())
        <section class="services-home">
            <div class="container">
                <div class="section-header-split">
                    <div class="reveal">
                        <span class="eyebrow">{{ content('home.services_label') }}</span>
                        <h2 class="section-title">{{ content('home.services_title') }}</h2>
                    </div>
                    <a href="{{ page_url('services') }}" class="btn btn-primary reveal reveal-delay-2">{{ content('home.services_button') }} <x-icon name="arrow" size="16" /></a>
                </div>
                <div class="service-card-grid count-{{ min($services->count(), 6) }}">
                    @foreach ($services as $service)
                        <a href="{{ page_url('services', null, $service->slug) }}" class="service-card reveal-scale reveal-delay-{{ min($loop->iteration, 4) }}">
                            @include('site.partials.image', ['src' => media_url($service->image), 'alt' => $service->tr('title'), 'class' => 'service-card-img', 'icon' => $service->icon])
                            <div class="service-card-overlay"></div>
                            <div class="service-card-content">
                                <span class="service-card-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <h3 class="service-card-title">{{ $service->tr('title') }}</h3>
                                <div class="service-card-line"></div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Featured project --}}
    @if ($featuredProject)
        <section class="featured">
            <div class="featured-wrapper">
                <div class="featured-image reveal-left">
                    @include('site.partials.image', ['src' => media_url($featuredProject->image), 'alt' => $featuredProject->tr('title'), 'icon' => $featuredProject->service?->icon])
                    <div class="featured-image-overlay"></div>
                </div>
                <div class="featured-content">
                    <div class="reveal">
                        <div class="featured-badge"><span>{{ content('home.featured_badge') }}</span></div>
                        <h3>{{ $featuredProject->tr('title') }}</h3>
                        <p>{{ $featuredProject->tr('description') }}</p>
                        @if ($stats = $featuredProject->trStats())
                            <div class="featured-stats">
                                @foreach (array_slice($stats, 0, 3) as $stat)
                                    <div class="featured-stat">
                                        <span class="featured-stat-value">{{ $stat['value'] }}</span>
                                        <span class="featured-stat-label">{{ $stat['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <a href="{{ page_url('projects') }}" class="btn btn-primary">{{ content('home.featured_button') }} <x-icon name="arrow" size="16" /></a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Projects --}}
    @if ($projects->isNotEmpty())
        <section class="projects-home">
            <div class="container">
                <div class="section-header-split">
                    <div class="reveal">
                        <span class="eyebrow">{{ content('home.projects_label') }}</span>
                        <h2 class="section-title">{{ content('home.projects_title') }}</h2>
                    </div>
                    <a href="{{ page_url('projects') }}" class="btn btn-outline reveal reveal-delay-2">{{ content('home.projects_button') }} <x-icon name="arrow" size="16" /></a>
                </div>
                <div class="project-tile-grid">
                    @foreach ($projects as $project)
                        <a href="{{ page_url('projects') }}" class="project-tile reveal-scale reveal-delay-{{ min($loop->iteration, 4) }}">
                            @include('site.partials.image', ['src' => media_url($project->image), 'alt' => $project->tr('title'), 'icon' => $project->service?->icon])
                            <div class="project-tile-overlay"></div>
                            <div class="project-tile-content">
                                <span class="project-tile-category">{{ $project->tr('category') }}</span>
                                <h3 class="project-tile-title">{{ $project->tr('title') }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Client logos --}}
    @if ($logoClients->isNotEmpty())
        <section class="clients-section">
            <div class="container">
                <div class="section-header-center reveal">
                    <span class="eyebrow eyebrow-center">{{ content('home.clients_label') }}</span>
                    <h2 class="section-title">{{ content('home.clients_title') }}</h2>
                </div>
                <div class="clients-logo-grid">
                    @foreach ($logoClients as $client)
                        @if ($client->url)
                            <a href="{{ $client->url }}" class="client-logo-card reveal-scale" target="_blank" rel="noopener"><img src="{{ media_url($client->logo) }}" alt="{{ $client->name }}" loading="lazy"></a>
                        @else
                            <div class="client-logo-card reveal-scale"><img src="{{ media_url($client->logo) }}" alt="{{ $client->name }}" loading="lazy"></div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('site.partials.cta')
@endsection
