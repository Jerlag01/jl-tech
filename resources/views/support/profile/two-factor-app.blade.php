@extends('support.layout', ['title' => __('support.two_factor.app_title')])

@section('portal')
    <x-support.hero :eyebrow="__('support.two_factor.eyebrow')" :title="__('support.two_factor.app_title')">
        {{ __('support.two_factor.app_intro') }}
    </x-support.hero>

    <div class="container portal-body portal-narrow">
        <div class="portal-card portal-padded portal-stack">
            <x-support.flash />
            <section>
                <h2 class="portal-card-title">1. {{ __('support.two_factor.app_step_install') }}</h2>
                <p class="portal-muted">{{ __('support.two_factor.app_step_install_text') }}</p>
            </section>
            <section>
                <h2 class="portal-card-title">2. {{ __('support.two_factor.app_step_scan') }}</h2>
                <div class="portal-qr">
                    <img src="{{ $qrCode }}" alt="{{ __('support.two_factor.qr_alt') }}">
                    <div>
                        <p class="portal-muted">{{ __('support.two_factor.app_manual') }}</p>
                        <p class="portal-secret" data-secret>{{ trim(chunk_split($secret, 4, ' ')) }}</p>
                    </div>
                </div>
            </section>
            <section>
                <h2 class="portal-card-title">3. {{ __('support.two_factor.app_step_confirm') }}</h2>
                <form method="POST" action="{{ support_route('two-factor.app.store') }}" class="portal-inline-form">
                    @csrf
                    <x-support.field name="code" :label="__('support.two_factor.code')" required autofocus inputmode="numeric" autocomplete="one-time-code" maxlength="10" />
                    <button type="submit" class="btn btn-primary">{{ __('support.two_factor.enable') }}</button>
                </form>
            </section>
            <a href="{{ support_route('profile.edit') }}" class="portal-accent-link">{{ __('support.two_factor.cancel') }}</a>
        </div>
    </div>
@endsection
