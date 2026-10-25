<div {{ $attributes->merge(['class' => 'portal-cta']) }}>
    <div>
        <h2>{{ __('support.kb.cta_title') }}</h2>
        <p>{{ __('support.kb.cta_text') }}</p>
    </div>
    <a href="{{ auth()->check() ? support_route('tickets.create') : support_route('login') }}" class="btn btn-primary">{{ __('support.nav.new_ticket') }} <x-site-icon name="arrow" size="16" /></a>
</div>
