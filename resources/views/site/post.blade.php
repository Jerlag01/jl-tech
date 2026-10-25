@extends('layouts.site', ['title' => $post->tr('title')])

@section('content')
    <header class="page-header post-header">
        <div class="page-header-grid"></div>
        <div class="container">
            <a href="{{ page_url('news') }}" class="page-label reveal">← {{ content('news.back') }}</a>
            <h1 class="reveal reveal-delay-1">{{ $post->tr('title') }}</h1>
            <p class="reveal reveal-delay-2">
                <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('j F Y') }}</time>
                @if ($post->author)
                    · {{ $post->author->name }}
                @endif
            </p>
        </div>
    </header>

    <article class="post-section">
        <div class="container post-container">
            @if ($post->image)
                <img src="{{ media_url($post->image) }}" alt="{{ $post->tr('title') }}" class="post-image">
            @endif
            <div class="rich-text post-body">{!! $post->bodyHtml() !!}</div>
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section class="news-section news-related">
            <div class="container">
                <div class="section-header-split">
                    <h2 class="section-title">{{ content('news.title') }}</h2>
                    <a href="{{ page_url('news') }}" class="btn btn-outline">{{ content('news.back') }}</a>
                </div>
                <div class="news-grid">
                    @foreach ($related as $item)
                        @include('site.partials.post-card', ['post' => $item])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
