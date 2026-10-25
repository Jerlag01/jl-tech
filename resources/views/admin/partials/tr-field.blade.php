{{--
    A translated input: one box per locale.
    Params: $name, $label, $value (array|null), $type (text|textarea|html), $rows, $help, $required
--}}
@php
    $type ??= 'text';
    $value = is_array($value ?? null) ? $value : [];
@endphp
<div class="field field-tr">
    <div class="field-label">{{ $label }} @if ($required ?? false)<span class="req">*</span>@endif</div>
    <div class="tr-grid">
        @foreach (config('site.locales') as $locale => $localeLabel)
            <label class="tr-cell">
                <span class="locale-tag">{{ strtoupper($locale) }}</span>
                @if ($type === 'text')
                    <input type="text" name="{{ $name }}[{{ $locale }}]" value="{{ old($name.'.'.$locale, $value[$locale] ?? '') }}" class="input" placeholder="{{ $localeLabel }}">
                @else
                    <textarea name="{{ $name }}[{{ $locale }}]" rows="{{ $rows ?? ($type === 'html' ? 10 : 4) }}" @class(['input', 'mono' => $type === 'html']) placeholder="{{ $localeLabel }}">{{ old($name.'.'.$locale, $value[$locale] ?? '') }}</textarea>
                @endif
            </label>
        @endforeach
    </div>
    @if (! empty($help))
        <div class="field-help">{{ $help }}</div>
    @endif
</div>
