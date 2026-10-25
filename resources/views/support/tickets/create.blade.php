@extends('support.layout', ['title' => __('support.nav.new_ticket')])

@section('portal')
    <x-support.hero :eyebrow="__('support.tickets.eyebrow')" :title="__('support.tickets.create_title')">
        {{ __('support.tickets.create_intro') }}
    </x-support.hero>

    <div class="container portal-body portal-columns">
        <form method="POST" action="{{ support_route('tickets.store') }}" enctype="multipart/form-data" class="portal-card portal-form" data-submit-once>
            @csrf
            <x-support.field name="subject" :label="__('support.fields.subject')" required maxlength="150" autofocus />
            <div class="form-row">
                <x-support.field name="help_topic_id" type="select" :label="__('support.fields.help_topic_id')" required>
                    <option value="" disabled @selected(! old('help_topic_id', request('topic')))>{{ __('support.tickets.choose') }}</option>
                    @foreach ($topics as $topic)
                        <option value="{{ $topic->id }}" @selected((string) old('help_topic_id', request('topic')) === (string) $topic->id)>{{ $topic->label() }}</option>
                    @endforeach
                </x-support.field>
                <x-support.field name="priority" type="select" :label="__('support.fields.priority')" required>
                    @foreach ($priorities as $priority)
                        <option value="{{ $priority->value }}" @selected(old('priority', request('priority', 'normal')) === $priority->value)>{{ $priority->getLabel() }}</option>
                    @endforeach
                </x-support.field>
            </div>
            <x-support.field name="site_address" :label="__('support.fields.site_address')" :hint="__('support.tickets.site_address_hint')" maxlength="255" />
            <x-support.field name="message" type="textarea" :label="__('support.fields.message')" :placeholder="__('support.tickets.message_placeholder')" required maxlength="10000" />
            <x-support.attachments />
            <div class="portal-form-footer">
                <a href="{{ support_route('tickets.index') }}" class="portal-back"><x-site-icon name="arrow-left" size="16" /> {{ __('support.tickets.back') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('support.tickets.submit') }} <x-site-icon name="arrow" size="16" /></button>
            </div>
        </form>

        <aside class="portal-aside">
            <div class="portal-card portal-padded">
                <span class="eyebrow">{{ __('support.tickets.tips_title') }}</span>
                <ul class="portal-checklist">
                    @foreach (__('support.tickets.tips') as $tip)
                        <li><x-site-icon name="check" size="16" /> {{ $tip }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="portal-card portal-card--dark portal-padded">
                <span class="eyebrow">{{ __('support.priorities.urgent') }}</span>
                <p>{{ __('support.home.urgent_text') }}</p>
                @if ($phone = content('contact.phone'))
                    <a href="tel:{{ preg_replace('/[^+\d]/', '', $phone) }}" class="portal-accent-link"><x-site-icon name="phone" size="16" /> {{ $phone }}</a>
                @endif
            </div>
        </aside>
    </div>
@endsection
