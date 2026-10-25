@extends('layouts.site', ['title' => __('Projects')])

@section('content')
    @include('site.partials.page-header', ['prefix' => 'projects'])

    <section class="projects-section">
        <div class="container">
            <div class="section-header-center reveal">
                <span class="eyebrow eyebrow-center">{{ content('projects.portfolio_label') }}</span>
                <h2 class="section-title">{{ content('projects.portfolio_title') }}</h2>
                <p>{{ content('projects.portfolio_text') }}</p>
            </div>

            <div class="projects-grid">
                @foreach ($highlighted as $project)
                    @include('site.partials.project-card', ['wide' => $loop->first])
                @endforeach

                @if ($highlighted->isNotEmpty() && $others->isNotEmpty())
                    <div class="section-divider"><span>{{ content('projects.divider') }}</span></div>
                @endif

                @foreach ($others as $project)
                    @include('site.partials.project-card', ['wide' => $highlighted->isEmpty() && $loop->first])
                @endforeach
            </div>
        </div>
    </section>

    @include('site.partials.cta')
@endsection
