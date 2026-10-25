@extends('support.layout', ['title' => __('support.two_factor.email_title')])

@section('portal')
    <x-support.hero :eyebrow="__('support.two_factor.eyebrow')" :title="__('support.two_factor.email_title')">
        {{ __('support.two_factor.email_intro', ['email' => $email]) }}
    </x-support.hero>

    <div class="container portal-body portal-narrow">
        <div class="portal-card portal-padded portal-stack">
            <x-support.flash />
            <form method="POST" action="{{ support_route('two-factor.email.store') }}" class="portal-inline-form">
                @csrf
                <x-support.field name="code" :label="__('support.two_factor.code')" required autofocus inputmode="numeric" autocomplete="one-time-code" maxlength="10" />
                <button type="submit" class="btn btn-primary">{{ __('support.two_factor.enable') }}</button>
            </form>
            <div class="portal-auth-row">
                <form method="POST" action="{{ support_route('two-factor.email.send') }}">
                    @csrf
                    <button type="submit" class="portal-link-button">{{ __('support.two_factor.resend') }}</button>
                </form>
                <a href="{{ support_route('profile.edit') }}" class="portal-accent-link">{{ __('support.two_factor.cancel') }}</a>
            </div>
        </div>
    </div>
@endsection
