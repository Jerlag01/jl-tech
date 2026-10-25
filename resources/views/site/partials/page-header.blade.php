{{-- Expects $prefix: the content key prefix with .label, .title and .text fields --}}
<header class="page-header">
    <div class="page-header-grid"></div>
    <div class="container">
        <span class="page-label reveal">{{ content($prefix.'.label') }}</span>
        <h1 class="reveal reveal-delay-1">{!! nl2br(e(content($prefix.'.title'))) !!}</h1>
        @if (content($prefix.'.text'))
            <p class="reveal reveal-delay-2">{{ content($prefix.'.text') }}</p>
        @endif
    </div>
</header>
