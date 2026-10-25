@if (session('status'))
    <div {{ $attributes->merge(['class' => 'form-alert form-alert--success']) }} role="status">{{ session('status') }}</div>
@endif
