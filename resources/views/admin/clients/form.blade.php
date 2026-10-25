@extends('layouts.admin', ['title' => $client->exists ? 'Edit client' : 'New client'])

@section('content')
    <form method="POST" action="{{ $client->exists ? route('admin.clients.update', $client) : route('admin.clients.store') }}" enctype="multipart/form-data" class="form">
        @csrf
        @if ($client->exists) @method('PUT') @endif

        <div class="panel narrow">
            @include('admin.partials.field', ['name' => 'name', 'label' => 'Name', 'value' => $client->name, 'required' => true])
            @include('admin.partials.field', ['name' => 'url', 'label' => 'Website', 'type' => 'url', 'value' => $client->url, 'help' => 'Optional; the logo links here.'])
            @include('admin.partials.image-field', ['name' => 'logo', 'label' => 'Logo', 'path' => $client->logo])
            @include('admin.partials.toggle', ['name' => 'in_marquee', 'label' => 'Show name in the scrolling band', 'checked' => $client->in_marquee])
            @include('admin.partials.toggle', ['name' => 'in_logo_grid', 'label' => 'Show logo in the partner grid', 'checked' => $client->in_logo_grid])
            @include('admin.partials.toggle', ['name' => 'active', 'label' => 'Visible on the website', 'checked' => $client->active])
            @include('admin.partials.sort-field', ['value' => $client->sort])
        </div>

        @include('admin.partials.form-actions', ['back' => route('admin.clients.index')])
    </form>
@endsection
