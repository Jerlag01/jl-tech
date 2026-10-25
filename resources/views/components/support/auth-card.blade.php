@props(['title', 'eyebrow' => null])
<section class="portal-auth">
    <div class="page-header-grid"></div>
    <div class="portal-auth-inner">
        <div class="portal-auth-card">
            @if ($eyebrow)
                <span class="eyebrow">{{ $eyebrow }}</span>
            @endif
            <h1>{{ $title }}</h1>
            <x-support.flash />
            {{ $slot }}
        </div>
        @isset($footer)
            <p class="portal-auth-footer">{{ $footer }}</p>
        @endisset
    </div>
</section>
