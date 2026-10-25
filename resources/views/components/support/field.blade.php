@props(['name', 'label', 'type' => 'text', 'required' => false, 'value' => null, 'hint' => null])
<div {{ $attributes->only('class')->merge(['class' => 'form-group']) }}>
    <label class="form-label" for="{{ $name }}">{{ $label }} @if ($required)<span class="portal-required">*</span>@endif</label>
    @if ($type === 'textarea')
        <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $attributes->get('rows', 7) }}" @required($required) {{ $attributes->except(['class', 'rows'])->class(['form-textarea', 'is-invalid' => $errors->has($name)]) }}>{{ old($name, $value) }}</textarea>
    @elseif ($type === 'select')
        <select id="{{ $name }}" name="{{ $name }}" @required($required) {{ $attributes->except('class')->class(['form-input', 'is-invalid' => $errors->has($name)]) }}>{{ $slot }}</select>
    @else
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" @if ($type !== 'password') value="{{ old($name, $value) }}" @endif @required($required) {{ $attributes->except('class')->class(['form-input', 'is-invalid' => $errors->has($name)]) }}>
    @endif
    @if ($hint)
        <p class="portal-hint">{{ $hint }}</p>
    @endif
    @error($name)
        <p class="portal-error">{{ $message }}</p>
    @enderror
</div>
