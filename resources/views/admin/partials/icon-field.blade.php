{{-- Params: $value --}}
@php($current = old('icon', $value ?? ''))
<div class="field">
    <span class="field-label">Icon</span>
    <div class="icon-picker">
        <label class="icon-option" title="None">
            <input type="radio" name="icon" value="" @checked($current === '' || $current === null)>
            <span>–</span>
        </label>
        @foreach (\App\Support\Icons::options() as $iconName => $iconLabel)
            <label class="icon-option" title="{{ $iconLabel }}">
                <input type="radio" name="icon" value="{{ $iconName }}" @checked($current === $iconName)>
                <span><x-icon :name="$iconName" size="22" /></span>
            </label>
        @endforeach
    </div>
</div>
