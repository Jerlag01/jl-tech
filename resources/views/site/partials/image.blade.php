{{-- Image with a branded placeholder when no file is uploaded. Expects $src, $alt, optional $class and $icon. --}}
@if ($src)
    <img src="{{ $src }}" alt="{{ $alt }}" class="{{ $class ?? '' }}" loading="lazy">
@else
    <div class="img-placeholder {{ $class ?? '' }}" role="img" aria-label="{{ $alt }}">
        <x-site-icon :name="$icon ?? 'bolt'" size="48" />
    </div>
@endif
