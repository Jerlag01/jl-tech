{{-- Filament's precompiled CSS only ships its own classes, so sizing here uses inline styles. --}}
<span style="display: flex; align-items: center; gap: .625rem; height: 2.25rem; white-space: nowrap;">
    @if ($logo = content_image('branding.logo'))
        <img src="{{ $logo }}" alt="" style="height: 2rem; width: auto; flex-shrink: 0;">
    @endif
    <span style="display: flex; flex-direction: column; line-height: 1.1;">
        <span style="color: inherit; font-size: .9375rem; font-weight: 800; letter-spacing: -.01em;">{{ content('company.name') }}</span>
        <span style="opacity: .6; font-size: .625rem; font-weight: 600; letter-spacing: .25em; text-transform: uppercase;">Admin</span>
    </span>
</span>
