@extends('support.layout', ['title' => $category->translate('name')])

@section('portal')
    <x-support.hero :eyebrow="__('support.nav.kb')" :title="$category->translate('name')">
        {{ $category->translate('description') }}
        <x-slot:actions>
            <a href="{{ support_route('kb.index') }}" class="btn btn-outline-light"><x-site-icon name="arrow-left" size="16" /> {{ __('support.kb.back') }}</a>
        </x-slot:actions>
    </x-support.hero>

    <div class="container portal-body portal-narrow">
        <div class="portal-stack">
            @forelse ($faqs as $faq)
                <details class="portal-card portal-faq">
                    <summary>{{ $faq->translate('question') }} <x-site-icon name="plus" size="16" /></summary>
                    <div class="rich-text portal-faq-answer">{{ $faq->renderedAnswer() }}</div>
                    @if ($faq->attachments)
                        <x-support.kb-downloads :faq="$faq" class="portal-faq-downloads" />
                    @endif
                    <a href="{{ support_route('kb.show', $faq) }}" class="portal-accent-link portal-faq-link">{{ __('support.kb.permalink') }}</a>
                </details>
            @empty
                <p class="portal-card portal-empty-row">{{ __('support.kb.empty') }}</p>
            @endforelse
        </div>

        <x-support.kb-cta />
    </div>
@endsection
