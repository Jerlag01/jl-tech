{{-- A single-language input. Params: $name, $label, $value, $type (text|textarea|email|url|number), $help, $required --}}
@php($type ??= 'text')
<label class="field">
    <span class="field-label">{{ $label }} @if ($required ?? false)<span class="req">*</span>@endif</span>
    @if ($type === 'textarea')
        <textarea name="{{ $name }}" rows="{{ $rows ?? 4 }}" class="input">{{ old($name, $value ?? '') }}</textarea>
    @else
        <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value ?? '') }}" class="input" @if ($type === 'number') step="any" @endif>
    @endif
    @if (! empty($help))
        <span class="field-help">{{ $help }}</span>
    @endif
</label>
