@php
    $hasApp = in_array('app', $methods, true);
    $hasEmail = in_array('email', $methods, true);
    // Start with the app when there is one, unless the user asked for (or only has) the e-mail code.
    $showEmail = $hasEmail && (! $hasApp || request('method') === 'email');
@endphp
@extends('support.layout', ['title' => __('support.two_factor.challenge_title')])

@section('portal')
    <x-support.auth-card :title="__('support.two_factor.challenge_title')" :eyebrow="__('support.two_factor.eyebrow')">
        @if ($showEmail)
            <p class="portal-muted">{{ __('support.two_factor.challenge_email') }}</p>
            <form method="POST" action="{{ support_route('two-factor.verify') }}" class="portal-form">
                @csrf
                <input type="hidden" name="method" value="email">
                <x-support.field name="code" :label="__('support.two_factor.code')" required autofocus inputmode="numeric" autocomplete="one-time-code" maxlength="10" />
                <button type="submit" class="btn btn-primary portal-full">{{ __('support.two_factor.verify') }}</button>
            </form>
            <form method="POST" action="{{ support_route('two-factor.send-email') }}" class="portal-center">
                @csrf
                <button type="submit" class="portal-link-button">{{ $emailSent ? __('support.two_factor.resend') : __('support.two_factor.send_email') }}</button>
            </form>
            @if ($hasApp)
                <p class="portal-center"><a href="{{ support_route('two-factor.challenge') }}" class="portal-accent-link">{{ __('support.two_factor.use_app') }}</a></p>
            @endif
        @else
            <p class="portal-muted">{{ __('support.two_factor.challenge_app') }}</p>
            <form method="POST" action="{{ support_route('two-factor.verify') }}" class="portal-form">
                @csrf
                <input type="hidden" name="method" value="app">
                <x-support.field name="code" :label="__('support.two_factor.code')" required autofocus inputmode="numeric" autocomplete="one-time-code" maxlength="10" />
                <button type="submit" class="btn btn-primary portal-full">{{ __('support.two_factor.verify') }}</button>
            </form>
            <details class="portal-recovery">
                <summary>{{ __('support.two_factor.lost_device') }}</summary>
                <p class="portal-muted">{{ __('support.two_factor.recovery_hint') }}</p>
                <form method="POST" action="{{ support_route('two-factor.verify') }}" class="portal-inline-form">
                    @csrf
                    <input type="hidden" name="method" value="recovery">
                    <label for="recovery_code" class="sr-only">{{ __('support.two_factor.recovery_code') }}</label>
                    <input id="recovery_code" name="code" type="text" class="form-input portal-mono" placeholder="xxxxxxxxxx-xxxxxxxxxx" autocomplete="off" required>
                    <button type="submit" class="btn btn-outline">{{ __('support.two_factor.verify') }}</button>
                </form>
            </details>
            @if ($hasEmail)
                <form method="POST" action="{{ support_route('two-factor.send-email') }}" class="portal-center">
                    @csrf
                    <button type="submit" class="portal-link-button">{{ __('support.two_factor.use_email') }}</button>
                </form>
            @endif
        @endif
        <x-slot:footer>
            <a href="{{ support_route('login') }}">{{ __('support.two_factor.back_to_login') }}</a>
        </x-slot:footer>
    </x-support.auth-card>
@endsection
