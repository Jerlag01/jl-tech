@extends('layouts.admin', ['title' => $service->exists ? 'Edit service' : 'New service'])

@section('content')
    <form method="POST" action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}" enctype="multipart/form-data" class="form">
        @csrf
        @if ($service->exists) @method('PUT') @endif

        <div class="form-layout">
            <div class="panel">
                @include('admin.partials.tr-field', ['name' => 'title', 'label' => 'Title', 'value' => $service->title, 'required' => true])
                @include('admin.partials.tr-field', ['name' => 'subtitle', 'label' => 'Subtitle (small orange text above the title)', 'value' => $service->subtitle])
                @include('admin.partials.tr-field', ['name' => 'description', 'label' => 'Description', 'value' => $service->description, 'type' => 'textarea', 'rows' => 5])
                @include('admin.partials.tr-field', ['name' => 'features', 'label' => 'Features', 'value' => $service->features, 'type' => 'textarea', 'rows' => 6, 'help' => 'One feature per line; each line becomes a bullet point.'])
                @include('admin.partials.stats-field', ['stats' => $service->stats, 'help' => 'Shown under the image, e.g. "200+ / Projects". Rows without a value are ignored.'])
            </div>

            <div class="panel">
                @include('admin.partials.toggle', ['name' => 'active', 'label' => 'Visible on the website', 'checked' => $service->active])
                @include('admin.partials.toggle', ['name' => 'show_on_home', 'label' => 'Show on the home page', 'checked' => $service->show_on_home])
                @include('admin.partials.image-field', ['name' => 'image', 'label' => 'Image', 'path' => $service->image])
                @include('admin.partials.icon-field', ['value' => $service->icon])
                @include('admin.partials.field', ['name' => 'slug', 'label' => 'URL anchor', 'value' => $service->slug, 'help' => 'Used in links like /nl/expertises#anchor. Generated from the title when empty.'])
                @include('admin.partials.sort-field', ['value' => $service->sort])
            </div>
        </div>

        @include('admin.partials.form-actions', ['back' => route('admin.services.index')])
    </form>
@endsection
