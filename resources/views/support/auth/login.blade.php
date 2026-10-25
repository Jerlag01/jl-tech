@extends('support.layout', ['title' => __('support.auth.login_title')])

@section('portal')
    <x-support.auth-card :title="__('support.auth.login_title')" :eyebrow="__('support.nav.tagline')">
        <form method="POST" action="{{ support_route('login') }}" class="portal-form">
            @csrf
            <x-support.field name="email" type="email" :label="__('support.fields.email')" required autofocus autocomplete="username" />
            <x-support.field name="password" type="password" :label="__('support.fields.password')" required autocomplete="current-password" />
            <div class="portal-auth-row">
                <label class="portal-check"><input type="checkbox" name="remember" class="form-checkbox"> {{ __('support.auth.remember') }}</label>
                <a href="{{ support_route('password.request') }}" class="portal-accent-link">{{ __('support.auth.forgot') }}</a>
            </div>
            <button type="submit" class="btn btn-primary portal-full">{{ __('support.nav.login') }}</button>
        </form>

        @if (helpdesk()->get('allow_registration'))
            <x-slot:footer>
                {{ __('support.auth.no_account') }}
                <a href="{{ support_route('register') }}">{{ __('support.nav.register') }}</a>
            </x-slot:footer>
        @endif
    </x-support.auth-card>
@endsection
