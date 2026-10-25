@extends('layouts.site', ['title' => __('page.team')])

@section('content')
    @include('site.partials.page-header', ['prefix' => 'team'])

    <section class="team-overview">
        <div class="container">
            <div class="section-header-center reveal">
                <span class="eyebrow eyebrow-center">{{ content('team.overview_label') }}</span>
                <h2 class="section-title">{{ content('team.overview_title') }}</h2>
                <p>{{ content('team.overview_text') }}</p>
            </div>
            @php($employees = key_figure('stats.employees'))
            @if ($employees || content('team.quality_value'))
                <div class="team-stats">
                    @if ($employees)
                        <div class="team-stat-card reveal-scale reveal-delay-1">
                            <div class="team-stat-number">±{{ $employees }}</div>
                            <div class="team-stat-label">{{ content('home.fig_employees_label') }}</div>
                        </div>
                    @endif
                    @if (content('team.quality_value'))
                        <div class="team-stat-card reveal-scale reveal-delay-2">
                            <div class="team-stat-number">{{ content('team.quality_value') }}</div>
                            <div class="team-stat-label">{{ content('team.quality_label') }}</div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>

    @php($photos = array_filter([content_image('team.photo_main'), content_image('team.photo_2'), content_image('team.photo_3')]))
    @if ($photos)
        <section class="team-photo-section">
            <div class="container">
                <div @class(['team-photo-grid', 'reveal-scale', 'single' => count($photos) === 1])>
                    @foreach (array_values($photos) as $index => $photo)
                        <div class="{{ $index === 0 ? 'team-photo-large' : 'team-photo-small' }}">
                            <img src="{{ $photo }}" alt="{{ content('team.photo_title') }}" loading="lazy">
                            @if ($index === 0)
                                <div class="photo-overlay">
                                    <h3>{{ content('team.photo_title') }}</h3>
                                    <p>{{ content('team.photo_text') }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($qualifications->isNotEmpty())
        <section class="qualifications">
            <div class="container">
                <div class="section-header-center reveal">
                    <span class="eyebrow eyebrow-center">{{ content('team.qualifications_label') }}</span>
                    <h2 class="section-title">{{ content('team.qualifications_title') }}</h2>
                    <p>{{ content('team.qualifications_text') }}</p>
                </div>
                <div class="card-grid-3">
                    @foreach ($qualifications as $qualification)
                        <div class="icon-card reveal-scale reveal-delay-{{ ($loop->index % 3) + 1 }}">
                            <div class="icon-card-icon"><x-icon :name="$qualification->icon" size="26" /></div>
                            <h4>{{ $qualification->tr('title') }}</h4>
                            <p>{{ $qualification->tr('body') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($fleet->isNotEmpty())
        <section class="fleet-section">
            <div class="container">
                <div class="section-header-center dark reveal">
                    <span class="eyebrow eyebrow-center">{{ content('team.fleet_label') }}</span>
                    <h2 class="section-title">{{ content('team.fleet_title') }}</h2>
                    <p>{{ content('team.fleet_text') }}</p>
                </div>
                <div class="fleet-grid">
                    @foreach ($fleet as $vehicle)
                        <div class="fleet-card reveal-scale reveal-delay-{{ min($loop->iteration, 4) }}">
                            <div class="fleet-icon"><x-icon :name="$vehicle->icon ?: 'truck'" size="28" /></div>
                            @if ($vehicle->value)
                                <div class="fleet-quantity">{{ $vehicle->value }}</div>
                            @endif
                            <div class="fleet-type">{{ $vehicle->tr('title') }}</div>
                            <p class="fleet-description">{{ $vehicle->tr('body') }}</p>
                        </div>
                    @endforeach
                </div>
                @if ($vehicles = key_figure('stats.vehicles'))
                    <div class="fleet-total reveal">
                        <div class="fleet-total-number">{{ $vehicles }}+</div>
                        <div class="fleet-total-label">{{ content('team.fleet_total_label') }}</div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    @include('site.partials.cta')
@endsection
