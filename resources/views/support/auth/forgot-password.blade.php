@extends('support.layout', ['title' => __('support.auth.forgot_title')])

@section('portal')
    <x-support.auth-card :title="__('support.auth.forgot_title')">
        <p class="portal-muted">{{ __('support.auth.forgot_intro') }}</p>
        <form method="POST" action="{{ support_route('password.email') }}" class="portal-form">
            @csrf
            <x-support.field name="email" type="email" :label="__('support.fields.email')" required autofocus autocomplete="username" />
            <button type="submit" class="btn btn-primary portal-full">{{ __('support.auth.send_link') }}</button>
        </form>
        <x-slot:footer>
            <a href="{{ support_route('login') }}">{{ __('support.auth.back_to_login') }}</a>
        </x-slot:footer>
    </x-support.auth-card>
@endsection
