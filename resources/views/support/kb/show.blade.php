@extends('support.layout', ['title' => $faq->translate('question')])

@section('portal')
    <x-support.hero :eyebrow="$faq->category->translate('name')" :title="$faq->translate('question')">
        <x-slot:actions>
            <a href="{{ support_route('kb.category', $faq->category) }}" class="btn btn-outline-light"><x-site-icon name="arrow-left" size="16" /> {{ $faq->category->translate('name') }}</a>
        </x-slot:actions>
    </x-support.hero>

    <div class="container portal-body portal-columns">
        <div class="portal-stack">
            <article class="portal-card portal-padded rich-text">{{ $faq->renderedAnswer() }}</article>
            <x-support.kb-downloads :faq="$faq" />
        </div>
        <aside class="portal-aside">
            @if ($related->isNotEmpty())
                <div class="portal-card portal-padded">
                    <span class="eyebrow">{{ __('support.kb.related') }}</span>
                    <ul class="portal-kb-faqs">
                        @foreach ($related as $item)
                            <li><a href="{{ support_route('kb.show', $item) }}">{{ $item->translate('question') }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <p class="portal-mono portal-muted">{{ __('support.kb.updated', ['date' => $faq->updated_at->format('d/m/Y')]) }}</p>
        </aside>
    </div>

    <div class="container portal-body-bottom"><x-support.kb-cta /></div>
@endsection
