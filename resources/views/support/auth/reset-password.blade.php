@extends('support.layout', ['title' => __('support.auth.reset_title')])

@section('portal')
    <x-support.auth-card :title="__('support.auth.reset_title')">
        <form method="POST" action="{{ support_route('password.store') }}" class="portal-form">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <x-support.field name="email" type="email" :label="__('support.fields.email')" :value="$request->email" required autocomplete="username" />
            <x-support.field name="password" type="password" :label="__('support.fields.password')" required autofocus autocomplete="new-password" />
            <x-support.field name="password_confirmation" type="password" :label="__('support.fields.password_confirmation')" required autocomplete="new-password" />
            <button type="submit" class="btn btn-primary portal-full">{{ __('support.auth.reset_button') }}</button>
        </form>
    </x-support.auth-card>
@endsection
