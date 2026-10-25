@extends('layouts.admin', ['title' => $jobOffer->exists ? 'Edit job offer' : 'New job offer'])

@section('content')
    <form method="POST" action="{{ $jobOffer->exists ? route('admin.jobs.update', $jobOffer) : route('admin.jobs.store') }}" class="form">
        @csrf
        @if ($jobOffer->exists) @method('PUT') @endif

        <div class="form-layout">
            <div class="panel">
                @include('admin.partials.tr-field', ['name' => 'title', 'label' => 'Job title', 'value' => $jobOffer->title, 'required' => true])
                @include('admin.partials.tr-field', ['name' => 'description', 'label' => 'Description', 'value' => $jobOffer->description, 'type' => 'textarea', 'rows' => 6])
                @include('admin.partials.tr-field', ['name' => 'tags', 'label' => 'Tags', 'value' => $jobOffer->tags, 'type' => 'textarea', 'rows' => 3, 'help' => 'One per line, e.g. contract type, location, hours.'])
            </div>
            <div class="panel">
                @include('admin.partials.toggle', ['name' => 'active', 'label' => 'Published', 'checked' => $jobOffer->active])
                @include('admin.partials.tr-field', ['name' => 'badge', 'label' => 'Badge (department)', 'value' => $jobOffer->badge])
                @include('admin.partials.sort-field', ['value' => $jobOffer->sort])
            </div>
        </div>

        @include('admin.partials.form-actions', ['back' => route('admin.jobs.index')])
    </form>
@endsection
