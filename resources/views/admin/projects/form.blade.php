@extends('layouts.admin', ['title' => $project->exists ? 'Edit project' : 'New project'])

@section('content')
    <form method="POST" action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}" enctype="multipart/form-data" class="form">
        @csrf
        @if ($project->exists) @method('PUT') @endif

        <div class="form-layout">
            <div class="panel">
                @include('admin.partials.tr-field', ['name' => 'title', 'label' => 'Title', 'value' => $project->title, 'required' => true])
                @include('admin.partials.tr-field', ['name' => 'category', 'label' => 'Category (small orange text)', 'value' => $project->category])
                @include('admin.partials.tr-field', ['name' => 'badge', 'label' => 'Badge on the image', 'value' => $project->badge, 'help' => 'Optional, e.g. "Featured" or "Safety".'])
                @include('admin.partials.tr-field', ['name' => 'description', 'label' => 'Description', 'value' => $project->description, 'type' => 'textarea', 'rows' => 5])
                @include('admin.partials.stats-field', ['stats' => $project->stats, 'help' => 'E.g. "12 km / of network". The first three also appear on the home page when the project is featured.'])
            </div>

            <div class="panel">
                @include('admin.partials.toggle', ['name' => 'active', 'label' => 'Visible on the website', 'checked' => $project->active])
                @include('admin.partials.toggle', ['name' => 'featured', 'label' => 'Featured on the home page', 'checked' => $project->featured, 'help' => 'The large block on the home page. Only one project can be featured.'])
                @include('admin.partials.toggle', ['name' => 'show_on_home', 'label' => 'Show in the home page project grid', 'checked' => $project->show_on_home])
                @include('admin.partials.toggle', ['name' => 'highlight', 'label' => 'Highlight on the Projects page', 'checked' => $project->highlight, 'help' => 'Highlighted projects are listed first; the first one is shown wide.'])
                @include('admin.partials.image-field', ['name' => 'image', 'label' => 'Image', 'path' => $project->image])
                <label class="field">
                    <span class="field-label">Related service</span>
                    <select name="service_id" class="input">
                        <option value="">–</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id', $project->service_id) == $service->id)>{{ $service->tr('title', 'nl') }}</option>
                        @endforeach
                    </select>
                    <span class="field-help">Used for the icon on the project card.</span>
                </label>
                @include('admin.partials.sort-field', ['value' => $project->sort])
            </div>
        </div>

        @include('admin.partials.form-actions', ['back' => route('admin.projects.index')])
    </form>
@endsection
