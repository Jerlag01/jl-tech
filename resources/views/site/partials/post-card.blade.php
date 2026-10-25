<a href="{{ route(app()->getLocale().'.news.show', $post) }}" class="post-card reveal">
    <div class="post-card-image">
        @include('site.partials.image', ['src' => media_url($post->image), 'alt' => $post->tr('title'), 'icon' => 'lightbulb'])
    </div>
    <div class="post-card-body">
        <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('j F Y') }}</time>
        <h3>{{ $post->tr('title') }}</h3>
        <p>{{ $post->summary() }}</p>
        <span class="post-card-more">{{ content('news.read_more') }} <x-site-icon name="arrow" size="16" /></span>
    </div>
</a>
