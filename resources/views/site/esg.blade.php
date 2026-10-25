@extends('layouts.site', ['title' => __('page.esg')])

@section('content')
    @include('site.partials.page-header', ['prefix' => 'esg'])

    @if (content('esg.intro'))
        <section class="esg-intro">
            <div class="container">
                <p class="esg-intro-content reveal">{{ content('esg.intro') }}</p>
            </div>
        </section>
    @endif

    @if ($pillars->isNotEmpty())
        <section class="esg-pillars">
            <div class="container">
                <div class="section-header-center reveal">
                    <span class="eyebrow eyebrow-center">{{ content('esg.pillars_label') }}</span>
                    <h2 class="section-title">{{ content('esg.pillars_title') }}</h2>
                </div>
                <div class="card-grid-3">
                    @foreach ($pillars as $pillar)
                        <div class="esg-card reveal reveal-delay-{{ min($loop->iteration, 4) }}">
                            <div class="icon-card-icon"><x-site-icon :name="$pillar->icon" size="26" /></div>
                            <h3>{{ $pillar->tr('title') }}</h3>
                            <p>{{ $pillar->tr('body') }}</p>
                            @if ($points = $pillar->trLines('list'))
                                <ul>
                                    @foreach ($points as $point)
                                        <li>{{ $point }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="esg-commitment">
        <div class="container">
            <div class="esg-commitment-content reveal">
                <h2>{{ content('esg.commitment_title') }}</h2>
                <p>{{ content('esg.commitment_text') }}</p>
                <div class="esg-stats">
                    @for ($i = 1; $i <= 3; $i++)
                        @if (content("esg.stat_{$i}_value"))
                            <div class="esg-stat">
                                <span class="esg-stat-number">{{ content("esg.stat_{$i}_value") }}</span>
                                <span class="esg-stat-label">{{ content("esg.stat_{$i}_label") }}</span>
                            </div>
                        @endif
                    @endfor
                </div>
            </div>
        </div>
    </section>

    @if ($actions->isNotEmpty())
        <section class="esg-actions">
            <div class="container">
                <div class="section-header-center reveal">
                    <span class="eyebrow eyebrow-center">{{ content('esg.actions_label') }}</span>
                    <h2 class="section-title">{{ content('esg.actions_title') }}</h2>
                </div>
                <div class="esg-actions-grid">
                    @foreach ($actions as $action)
                        <div class="esg-action-item reveal">
                            <div class="esg-action-icon"><x-site-icon :name="$action->icon" size="22" /></div>
                            <div>
                                <h4>{{ $action->tr('title') }}</h4>
                                <p>{{ $action->tr('body') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
