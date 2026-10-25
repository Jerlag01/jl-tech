@props(['eyebrow' => null, 'title'])
<header class="page-header portal-hero">
    <div class="page-header-grid"></div>
    <div class="container portal-hero-inner">
        <div>
            @if ($eyebrow)
                <span class="page-label">{{ $eyebrow }}</span>
            @endif
            <h1>{{ $title }}</h1>
            @if (trim($slot) !== '')
                <div class="portal-hero-text">{{ $slot }}</div>
            @endif
        </div>
        @isset($actions)
            <div class="portal-hero-actions">{{ $actions }}</div>
        @endisset
    </div>
</header>
