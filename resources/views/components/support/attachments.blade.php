@php($rules = \App\Http\Requests\AttachmentRules::class)
<div class="form-group">
    <span class="form-label">{{ __('support.fields.attachments') }}</span>
    <label for="attachments" class="portal-dropzone">
        <x-site-icon name="paperclip" size="20" />
        <span>{{ __('support.tickets.attachments_hint', ['count' => $rules::MAX_FILES, 'size' => $rules::MAX_KILOBYTES / 1024]) }}</span>
    </label>
    <input id="attachments" name="attachments[]" type="file" multiple data-file-input class="sr-only"
           accept="{{ collect($rules::EXTENSIONS)->map(fn ($extension) => '.'.$extension)->implode(',') }}">
    <ul data-file-list="attachments" class="portal-file-list"></ul>
    @error('attachments')
        <p class="portal-error">{{ $message }}</p>
    @enderror
    @foreach ($errors->get('attachments.*') as $messages)
        <p class="portal-error">{{ $messages[0] }}</p>
    @endforeach
</div>
