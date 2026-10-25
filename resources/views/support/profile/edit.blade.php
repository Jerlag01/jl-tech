@extends('support.layout', ['title' => __('support.nav.profile')])

@section('portal')
    <x-support.hero :eyebrow="__('support.nav.profile')" :title="__('support.profile.title')" />

    <div class="container portal-body portal-narrow">
        <x-support.flash />

        <form method="POST" action="{{ support_route('profile.update') }}" class="portal-card portal-form">
            @csrf
            @method('PUT')
            <fieldset>
                <legend class="portal-card-title">{{ __('support.profile.details') }}</legend>
                <div class="form-row">
                    <x-support.field name="name" :label="__('support.fields.name')" :value="$user->name" required autocomplete="name" />
                    <x-support.field name="company" :label="__('support.fields.company')" :value="$user->company" autocomplete="organization" />
                </div>
                <div class="form-row">
                    <x-support.field name="email" type="email" :label="__('support.fields.email')" :value="$user->email" required autocomplete="email" />
                    <x-support.field name="phone" type="tel" :label="__('support.fields.phone')" :value="$user->phone" autocomplete="tel" />
                </div>
                <x-support.field name="locale" type="select" :label="__('support.fields.locale')" :hint="__('support.profile.locale_hint')" required>
                    @foreach (config('site.locales') as $code => $language)
                        <option value="{{ $code }}" @selected(old('locale', $user->locale) === $code)>{{ $language }}</option>
                    @endforeach
                </x-support.field>
            </fieldset>
            <fieldset class="portal-fieldset-divider">
                <legend class="portal-card-title">{{ __('support.profile.password') }}</legend>
                <x-support.field name="current_password" type="password" :label="__('support.fields.current_password')" autocomplete="current-password" :hint="__('support.profile.password_hint')" />
                <div class="form-row">
                    <x-support.field name="password" type="password" :label="__('support.fields.new_password')" autocomplete="new-password" />
                    <x-support.field name="password_confirmation" type="password" :label="__('support.fields.password_confirmation')" autocomplete="new-password" />
                </div>
            </fieldset>
            <div class="portal-form-footer portal-form-footer--end">
                <button type="submit" class="btn btn-primary">{{ __('support.profile.save') }}</button>
            </div>
        </form>

        @php
            $hasApp = filled($user->app_authentication_secret);
            $recoveryLeft = $hasApp ? count($user->app_authentication_recovery_codes ?? []) : 0;
        @endphp
        <section id="two-factor" class="portal-card portal-padded portal-two-factor">
            <h2 class="portal-card-title">{{ __('support.two_factor.section_title') }}</h2>
            <p class="portal-muted">{{ __('support.two_factor.section_intro') }}</p>

            <x-support.two-factor-reminder />

            @if (session('recovery_codes'))
                <div class="portal-recovery-codes" data-recovery-codes>
                    <strong>{{ __('support.two_factor.recovery_title') }}</strong>
                    <p class="portal-muted">{{ __('support.two_factor.recovery_text') }}</p>
                    <ul>
                        @foreach (session('recovery_codes') as $code)
                            <li>{{ $code }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="portal-method">
                <div>
                    <strong>{{ __('support.two_factor.method_app') }}</strong>
                    <span @class(['portal-badge', 'portal-badge--resolved' => $hasApp, 'portal-badge--closed' => ! $hasApp])>{{ $hasApp ? __('support.two_factor.on') : __('support.two_factor.off') }}</span>
                    <p class="portal-muted">{{ __('support.two_factor.method_app_text') }}</p>
                    @if ($hasApp)
                        <p class="portal-hint">{{ __('support.two_factor.recovery_left', ['count' => $recoveryLeft]) }}</p>
                    @endif
                </div>
                @unless ($hasApp)
                    <a href="{{ support_route('two-factor.app.create') }}" class="btn btn-primary btn-small">{{ __('support.two_factor.set_up') }}</a>
                @endunless
            </div>
            @if ($hasApp)
                <div class="form-row">
                    <form method="POST" action="{{ support_route('two-factor.recovery-codes') }}" class="portal-subform">
                        @csrf
                        <strong>{{ __('support.two_factor.new_recovery_codes') }}</strong>
                        <input name="recovery_password" type="password" class="form-input" placeholder="{{ __('support.fields.current_password') }}" aria-label="{{ __('support.fields.current_password') }}" autocomplete="current-password" required>
                        @error('recovery_password', 'twoFactorRecovery') <p class="portal-error">{{ $message }}</p> @enderror
                        <button type="submit" class="btn btn-outline btn-small">{{ __('support.two_factor.generate') }}</button>
                    </form>
                    <form method="POST" action="{{ support_route('two-factor.app.destroy') }}" class="portal-subform">
                        @csrf
                        @method('DELETE')
                        <strong>{{ __('support.two_factor.switch_off') }}</strong>
                        <input name="app_password" type="password" class="form-input" placeholder="{{ __('support.fields.current_password') }}" aria-label="{{ __('support.fields.current_password') }}" autocomplete="current-password" required>
                        @error('app_password', 'twoFactorApp') <p class="portal-error">{{ $message }}</p> @enderror
                        <button type="submit" class="btn btn-outline btn-small portal-danger">{{ __('support.two_factor.switch_off') }}</button>
                    </form>
                </div>
            @endif

            <div class="portal-method">
                <div>
                    <strong>{{ __('support.two_factor.method_email') }}</strong>
                    <span @class(['portal-badge', 'portal-badge--resolved' => $user->has_email_authentication, 'portal-badge--closed' => ! $user->has_email_authentication])>{{ $user->has_email_authentication ? __('support.two_factor.on') : __('support.two_factor.off') }}</span>
                    <p class="portal-muted">{{ __('support.two_factor.method_email_text', ['email' => $user->email]) }}</p>
                </div>
                @unless ($user->has_email_authentication)
                    <form method="POST" action="{{ support_route('two-factor.email.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-small">{{ __('support.two_factor.set_up') }}</button>
                    </form>
                @endunless
            </div>
            @if ($user->has_email_authentication)
                <form method="POST" action="{{ support_route('two-factor.email.destroy') }}" class="portal-subform">
                    @csrf
                    @method('DELETE')
                    <strong>{{ __('support.two_factor.switch_off') }}</strong>
                    <input name="email_password" type="password" class="form-input" placeholder="{{ __('support.fields.current_password') }}" aria-label="{{ __('support.fields.current_password') }}" autocomplete="current-password" required>
                    @error('email_password', 'twoFactorEmail') <p class="portal-error">{{ $message }}</p> @enderror
                    <button type="submit" class="btn btn-outline btn-small portal-danger">{{ __('support.two_factor.switch_off') }}</button>
                </form>
            @endif
        </section>
    </div>
@endsection
