{{-- Params: $name, $label, $checked, $help --}}
<label class="toggle">
    <input type="hidden" name="{{ $name }}" value="0">
    <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $checked ?? false))>
    <span class="toggle-ui"></span>
    <span>
        <span class="toggle-label">{{ $label }}</span>
        @if (! empty($help))
            <span class="field-help">{{ $help }}</span>
        @endif
    </span>
</label>
