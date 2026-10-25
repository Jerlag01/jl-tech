@props(['footer' => false])
@php
    $logo = $footer ? (content_image('branding.logo_footer') ?? content_image('branding.logo')) : content_image('branding.logo');
@endphp
@if ($logo)
    <img src="{{ $logo }}" alt="{{ content('company.name') }}" class="{{ $footer ? 'footer-logo' : 'logo' }}">
@else
    <span class="logo-text">{{ content('company.name') }}</span>
@endif
