@extends('layouts.admin', ['title' => ($item->exists ? 'Edit' : 'New').' – '.$config['label']])

@section('content')
    @php($fields = $config['fields'])
    <form method="POST" action="{{ $item->exists ? route('admin.items.update', $item) : route('admin.items.store', $group) }}" enctype="multipart/form-data" class="form">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="form-layout">
            <div class="panel">
                @isset($fields['value'])
                    @include('admin.partials.field', ['name' => 'value', 'label' => $fields['value'], 'value' => $item->value])
                @endisset
                @include('admin.partials.tr-field', ['name' => 'title', 'label' => $fields['title'], 'value' => $item->title, 'required' => true])
                @isset($fields['subtitle'])
                    @include('admin.partials.tr-field', ['name' => 'subtitle', 'label' => $fields['subtitle'], 'value' => $item->subtitle])
                @endisset
                @isset($fields['body'])
                    @include('admin.partials.tr-field', ['name' => 'body', 'label' => $fields['body'], 'value' => $item->body, 'type' => 'textarea'])
                @endisset
                @isset($fields['list'])
                    @include('admin.partials.tr-field', ['name' => 'list', 'label' => $fields['list'], 'value' => $item->list, 'type' => 'textarea', 'rows' => 5])
                @endisset
            </div>
            <div class="panel">
                @include('admin.partials.toggle', ['name' => 'active', 'label' => 'Visible on the website', 'checked' => $item->active])
                @isset($fields['image'])
                    @include('admin.partials.image-field', ['name' => 'image', 'label' => $fields['image'], 'path' => $item->image])
                @endisset
                @isset($fields['icon'])
                    @include('admin.partials.icon-field', ['value' => $item->icon])
                @endisset
                @include('admin.partials.sort-field', ['value' => $item->sort])
            </div>
        </div>

        @include('admin.partials.form-actions', ['back' => route('admin.items.index', $group)])
    </form>
@endsection
