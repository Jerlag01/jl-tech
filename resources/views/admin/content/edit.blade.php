@extends('layouts.admin', ['title' => $config['label']])

@use('App\Http\Controllers\Admin\ContentController')
@use('App\Models\Content')

@section('content')
    @if (! empty($config['page']))
        <p class="page-intro">
            Texts on the <strong>{{ $config['label'] }}</strong>.
            <a href="{{ page_url($config['page'], 'nl') }}" target="_blank" rel="noopener">View NL ↗</a> ·
            <a href="{{ page_url($config['page'], 'en') }}" target="_blank" rel="noopener">View EN ↗</a>
        </p>
    @endif

    <form method="POST" action="{{ route('admin.content.update', $group) }}" enctype="multipart/form-data" class="panel form">
        @csrf
        @method('PUT')

        @foreach ($config['fields'] as $key => $field)
            @php
                $input = ContentController::inputName($key);
                $raw = Content::raw($key);
            @endphp

            @if ($field['type'] === 'image')
                @include('admin.partials.image-field', ['name' => $input, 'label' => $field['label'], 'path' => $raw, 'help' => $field['help'] ?? null])
            @elseif ($field['type'] === 'boolean')
                @include('admin.partials.toggle', ['name' => $input, 'label' => $field['label'], 'checked' => (bool) $raw, 'help' => $field['help'] ?? null])
            @elseif ($field['tr'] ?? false)
                @include('admin.partials.tr-field', ['name' => $input, 'label' => $field['label'], 'value' => $raw, 'type' => $field['type'] === 'text' ? 'text' : $field['type'], 'help' => $field['help'] ?? null])
            @else
                @include('admin.partials.field', ['name' => $input, 'label' => $field['label'], 'value' => $raw, 'type' => $field['type'], 'help' => $field['help'] ?? null])
            @endif
        @endforeach

        <div class="form-actions sticky">
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
    </form>
@endsection
