{{-- Image upload with preview and remove option. Params: $name, $label, $path (current file), $help --}}
@php($previewId = 'preview-'.str_replace(['[', ']', '.'], '-', $name))
<div class="field">
    <span class="field-label">{{ $label }}</span>
    <div class="image-field">
        <img id="{{ $previewId }}" src="{{ media_url($path ?? null) }}" alt="" class="image-preview" @if (empty($path)) hidden @endif>
        <div>
            <input type="file" name="{{ $name }}" accept="image/*" data-preview="{{ $previewId }}" class="input-file">
            @if (! empty($path))
                <label class="check"><input type="checkbox" name="remove_{{ $name }}" value="1"> Remove current image</label>
            @endif
            <span class="field-help">{{ $help ?? 'JPG, PNG, WebP, GIF or SVG – max 10 MB.' }}</span>
        </div>
    </div>
</div>
