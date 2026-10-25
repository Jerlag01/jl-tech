@extends('layouts.site', ['title' => __('Careers')])

@section('content')
    @include('site.partials.page-header', ['prefix' => 'jobs'])

    @php($email = content('contact.email'))

    <section class="jobs-intro">
        <div class="container">
            <div class="jobs-intro-grid">
                <div class="reveal-left">
                    <span class="eyebrow">{{ content('jobs.intro_label') }}</span>
                    <h2 class="section-title">{{ content('jobs.intro_title') }}</h2>
                    <p class="intro-text">{{ content('jobs.intro_text') }}</p>
                </div>
                <div class="jobs-intro-stats reveal-right">
                    <div class="jobs-intro-stat">
                        <span class="jobs-intro-stat-value">±{{ content('stats.employees') }}</span>
                        <span class="jobs-intro-stat-label">{{ content('home.fig_employees_label') }}</span>
                    </div>
                    <div class="jobs-intro-stat">
                        <span class="jobs-intro-stat-value">{{ content('company.founded_year') }}</span>
                        <span class="jobs-intro-stat-label">{{ content('home.fig_founded_label') }}</span>
                    </div>
                    <div class="jobs-intro-stat">
                        <span class="jobs-intro-stat-value">{{ $serviceCount }}</span>
                        <span class="jobs-intro-stat-label">{{ __('Areas of expertise') }}</span>
                    </div>
                    <div class="jobs-intro-stat">
                        <span class="jobs-intro-stat-value">{{ content('stats.vehicles') }}+</span>
                        <span class="jobs-intro-stat-label">{{ content('home.fig_vehicles_label') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="jobs-section">
        <div class="container">
            <div class="reveal jobs-header">
                <span class="eyebrow">{{ content('jobs.list_label') }}</span>
                <h2 class="section-title">{{ content('jobs.list_title') }}</h2>
            </div>

            @if ($jobs->isEmpty())
                <div class="jobs-empty reveal">
                    <div class="jobs-empty-icon"><x-icon name="briefcase" size="32" /></div>
                    <h3>{{ content('jobs.empty_title') }}</h3>
                    <p>{{ content('jobs.empty_text') }}</p>
                    @if ($email)
                        <a href="mailto:{{ $email }}?subject={{ rawurlencode(__('Open application')) }}" class="btn btn-primary">{{ content('jobs.empty_button') }} <x-icon name="arrow" size="16" /></a>
                    @endif
                </div>
            @else
                <div class="jobs-grid">
                    @foreach ($jobs as $job)
                        <article class="job-card reveal reveal-delay-{{ ($loop->index % 3) + 1 }}">
                            @if ($job->tr('badge'))
                                <span class="job-card-badge">{{ $job->tr('badge') }}</span>
                            @endif
                            <h3 class="job-card-title">{{ $job->tr('title') }}</h3>
                            @if ($tags = $job->trLines('tags'))
                                <div class="job-card-tags">
                                    @foreach ($tags as $tag)
                                        <span class="job-card-tag"><x-icon name="tag" size="12" /> {{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <p class="job-card-description">{{ $job->tr('description') }}</p>
                            <a href="{{ $email ? 'mailto:'.$email.'?subject='.rawurlencode($job->tr('title')) : page_url('contact') }}" class="job-card-action">{{ content('jobs.apply_button') }} <x-icon name="arrow" size="16" /></a>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
