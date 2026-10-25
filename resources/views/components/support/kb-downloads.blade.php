@props(['faq'])
@if ($downloads = $faq->downloads())
    <div {{ $attributes->merge(['class' => 'portal-card portal-downloads']) }}>
        <span class="eyebrow">{{ __('support.kb.downloads') }}</span>
        <ul>
            @foreach ($downloads as $download)
                <li>
                    <a href="{{ $download['url'] }}" download="{{ $download['name'] }}">
                        <x-site-icon name="paperclip" size="16" />
                        <span class="portal-truncate">{{ $download['name'] }}</span>
                        <span class="portal-mono">{{ $download['size'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
