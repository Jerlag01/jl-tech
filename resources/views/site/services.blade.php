@extends('layouts.site', ['title' => __('Services')])

@section('content')
    @include('site.partials.page-header', ['prefix' => 'services'])

    @foreach ($services as $service)
        <section id="{{ $service->slug }}" @class(['service-section', 'service-section--reverse' => $loop->even])>
            <div class="container">
                <div class="service-grid">
                    <div class="service-image-wrapper {{ $loop->even ? 'reveal-right' : 'reveal-left' }}">
                        <div class="service-image-container">
                            <div class="service-icon-badge"><x-site-icon :name="$service->icon" size="28" /></div>
                            @include('site.partials.image', ['src' => media_url($service->image), 'alt' => $service->tr('title'), 'icon' => $service->icon])
                        </div>
                        @if ($stats = $service->trStats())
                            <div class="service-stats">
                                @foreach ($stats as $stat)
                                    <div class="stat-item">
                                        <span class="stat-value">{{ $stat['value'] }}</span>
                                        <span class="stat-label">{{ $stat['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="service-content {{ $loop->even ? 'reveal-left' : 'reveal-right' }}">
                        @if ($service->tr('subtitle'))
                            <span class="eyebrow">{{ $service->tr('subtitle') }}</span>
                        @endif
                        <h2>{{ $service->tr('title') }}</h2>
                        <p class="service-description">{{ $service->tr('description') ?: $service->tr('summary') }}</p>
                        @if ($features = $service->trLines('features'))
                            <ul class="features-list">
                                @foreach ($features as $feature)
                                    <li><x-site-icon name="check" size="18" /> <span>{{ $feature }}</span></li>
                                @endforeach
                            </ul>
                        @endif
                        <a href="{{ page_url('projects') }}" class="btn btn-primary">{{ content('services.button') }} <x-site-icon name="arrow" size="16" /></a>
                    </div>
                </div>
            </div>
        </section>
    @endforeach

    @include('site.partials.cta')
@endsection
