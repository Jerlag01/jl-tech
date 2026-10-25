@extends('support.layout', ['title' => __('support.auth.register_title')])

@section('portal')
    <x-support.auth-card :title="__('support.auth.register_title')" :eyebrow="__('support.nav.tagline')">
        <p class="portal-muted">{{ __('support.auth.register_intro') }}</p>
        <form method="POST" action="{{ support_route('register') }}" class="portal-form">
            @csrf
            <x-support.field name="name" :label="__('support.fields.name')" required autofocus autocomplete="name" />
            <x-support.field name="company" :label="__('support.fields.company')" autocomplete="organization" />
            <x-support.field name="email" type="email" :label="__('support.fields.email')" required autocomplete="email" />
            <x-support.field name="phone" type="tel" :label="__('support.fields.phone')" autocomplete="tel" />
            <x-support.field name="password" type="password" :label="__('support.fields.password')" required autocomplete="new-password" />
            <x-support.field name="password_confirmation" type="password" :label="__('support.fields.password_confirmation')" required autocomplete="new-password" />
            <button type="submit" class="btn btn-primary portal-full">{{ __('support.auth.register_button') }}</button>
        </form>
        <x-slot:footer>
            {{ __('support.auth.has_account') }}
            <a href="{{ support_route('login') }}">{{ __('support.nav.login') }}</a>
        </x-slot:footer>
    </x-support.auth-card>
@endsection
