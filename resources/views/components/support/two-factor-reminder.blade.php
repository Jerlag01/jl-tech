{{-- Recommends two-step verification to signed-in users who have not set it up yet. --}}
@auth
    @unless (auth()->user()->hasTwoFactor())
        <div {{ $attributes->merge(['class' => 'portal-reminder']) }} data-two-factor-reminder>
            <x-site-icon name="shield" size="24" />
            <div>
                <strong>{{ __('support.two_factor.reminder_title') }}</strong>
                <p>{{ __('support.two_factor.reminder_text') }}</p>
            </div>
            @unless (str((string) request()->route()?->getName())->endsWith('support.profile.edit'))
                <a href="{{ support_route('profile.edit') }}#two-factor" class="btn btn-outline btn-small">{{ __('support.two_factor.reminder_action') }}</a>
            @endunless
        </div>
    @endunless
@endauth
