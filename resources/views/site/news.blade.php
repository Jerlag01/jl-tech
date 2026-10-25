@extends('layouts.site', ['title' => __('News')])

@section('content')
    @include('site.partials.page-header', ['prefix' => 'news'])

    <section class="news-section">
        <div class="container">
            <div class="news-grid">
                @foreach ($posts as $post)
                    @include('site.partials.post-card', ['post' => $post])
                @endforeach
            </div>

            @if ($posts->hasPages())
                <nav class="news-pagination">
                    @if ($posts->previousPageUrl())<a href="{{ $posts->previousPageUrl() }}" class="btn btn-outline">←</a>@endif
                    <span>{{ $posts->currentPage() }} / {{ $posts->lastPage() }}</span>
                    @if ($posts->nextPageUrl())<a href="{{ $posts->nextPageUrl() }}" class="btn btn-outline">→</a>@endif
                </nav>
            @endif
        </div>
    </section>
@endsection
