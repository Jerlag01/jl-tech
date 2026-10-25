@extends('support.layout', ['title' => __('support.nav.kb')])

@section('portal')
    <x-support.hero :eyebrow="__('support.kb.eyebrow')" :title="__('support.kb.title')">
        {{ __('support.kb.intro') }}
    </x-support.hero>

    <div class="container portal-body">
        <form method="GET" action="{{ support_route('kb.index') }}" class="portal-search portal-search--large" role="search">
            <label for="search" class="sr-only">{{ __('support.kb.search') }}</label>
            <x-site-icon name="search" size="20" />
            <input id="search" name="search" type="search" value="{{ $search }}" placeholder="{{ __('support.kb.search') }}" class="form-input">
        </form>

        @if ($results !== null)
            <section class="portal-results">
                <h2 class="portal-card-title">{{ trans_choice('support.kb.results', $results->count(), ['search' => $search]) }}</h2>
                <ul class="portal-card portal-link-list">
                    @forelse ($results as $faq)
                        <li><a href="{{ support_route('kb.show', $faq) }}"><span><strong>{{ $faq->translate('question') }}</strong><span class="portal-muted">{{ $faq->category->translate('name') }}</span></span><x-site-icon name="arrow" size="16" /></a></li>
                    @empty
                        <li class="portal-empty-row">{{ __('support.kb.no_results') }}</li>
                    @endforelse
                </ul>
            </section>
        @endif

        <div class="portal-kb-grid">
            @forelse ($categories as $category)
                <section class="portal-card portal-padded reveal">
                    <div class="portal-kb-head">
                        <span class="portal-icon-box"><x-site-icon name="book" size="20" /></span>
                        <div>
                            <h2><a href="{{ support_route('kb.category', $category) }}">{{ $category->translate('name') }}</a></h2>
                            @if (filled($category->translate('description')))
                                <p class="portal-muted">{{ $category->translate('description') }}</p>
                            @endif
                        </div>
                    </div>
                    <ul class="portal-kb-faqs">
                        @foreach ($category->publishedFaqs->take(5) as $faq)
                            <li><a href="{{ support_route('kb.show', $faq) }}"><x-site-icon name="chat" size="16" /> {{ $faq->translate('question') }}</a></li>
                        @endforeach
                    </ul>
                    @if ($category->publishedFaqs->count() > 5)
                        <a href="{{ support_route('kb.category', $category) }}" class="portal-accent-link">{{ __('support.kb.all_articles', ['count' => $category->publishedFaqs->count()]) }} <x-site-icon name="arrow" size="16" /></a>
                    @endif
                </section>
            @empty
                <div class="portal-card portal-empty">
                    <x-site-icon name="book" size="40" />
                    <p><strong>{{ __('support.kb.empty') }}</strong></p>
                </div>
            @endforelse
        </div>

        <x-support.kb-cta />
    </div>
@endsection
