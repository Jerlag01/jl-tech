@extends('layouts.site', ['title' => __('page.history')])

@section('content')
    @include('site.partials.page-header', ['prefix' => 'history'])

    <section class="history-intro">
        <div class="container">
            <div class="history-intro-grid">
                <div class="history-intro-text reveal-left">
                    <h2 class="section-title">{{ content('history.intro_title') }}</h2>
                    <div class="rich-text">{!! content('history.intro_text') !!}</div>
                </div>
                <div class="history-intro-stats reveal-right">
                    @for ($i = 1; $i <= 4; $i++)
                        @if (content("history.stat_{$i}_value"))
                            <div class="intro-stat">
                                <div class="intro-stat-number">{{ content("history.stat_{$i}_value") }}</div>
                                <div class="intro-stat-label">{{ content("history.stat_{$i}_label") }}</div>
                            </div>
                        @endif
                    @endfor
                </div>
            </div>
        </div>
    </section>

    @if ($timeline->isNotEmpty())
        <section class="timeline-section">
            <div class="container">
                <div class="section-header-center reveal">
                    <span class="eyebrow eyebrow-center">{{ content('history.timeline_label') }}</span>
                    <h2 class="section-title">{{ content('history.timeline_title') }}</h2>
                </div>
                <div class="timeline-scroll" tabindex="0">
                    @foreach ($timeline as $event)
                        <div class="timeline-card">
                            <div class="timeline-year-badge">{{ $event->value }}</div>
                            <h3>{{ $event->tr('title') }}</h3>
                            <p>{{ $event->tr('body') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($milestones->isNotEmpty())
        <section class="milestones-section">
            <div class="container">
                <div class="section-header-center reveal">
                    <span class="eyebrow eyebrow-center">{{ content('history.milestones_label') }}</span>
                    <h2 class="section-title">{{ content('history.milestones_title') }}</h2>
                </div>
                <div class="milestones-grid">
                    @foreach ($milestones as $milestone)
                        <div class="milestone-card reveal reveal-delay-{{ min($loop->iteration, 4) }}">
                            <div class="milestone-number">{{ $milestone->value }}</div>
                            <h4>{{ $milestone->tr('title') }}</h4>
                            <p>{{ $milestone->tr('body') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($leaders->isNotEmpty())
        <section class="leadership-section">
            <div class="container">
                <div class="section-header-center reveal">
                    <span class="eyebrow eyebrow-center">{{ content('history.leaders_label') }}</span>
                    <h2 class="section-title">{{ content('history.leaders_title') }}</h2>
                </div>
                <div class="leadership-grid">
                    @foreach ($leaders as $leader)
                        <div class="leader-card reveal reveal-delay-{{ min($loop->iteration, 4) }}">
                            @if ($leader->image)
                                <img src="{{ media_url($leader->image) }}" alt="{{ $leader->tr('title') }}" class="leader-photo" loading="lazy">
                            @else
                                <div class="leader-icon"><x-site-icon :name="$leader->icon ?: 'user'" size="28" /></div>
                            @endif
                            <h3>{{ $leader->tr('title') }}</h3>
                            <div class="leader-role">{{ $leader->tr('subtitle') }}</div>
                            <p>{{ $leader->tr('body') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('site.partials.cta')
@endsection
