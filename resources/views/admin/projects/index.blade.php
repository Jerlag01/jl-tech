@extends('layouts.admin', ['title' => 'Projects'])

@section('content')
    <div class="toolbar">
        <p class="muted">Shown on the Projects page. Mark one as "featured" for the big block on the home page.</p>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">+ Add project</a>
    </div>

    <div class="panel table-wrap">
        <table class="table">
            <thead><tr><th></th><th>Title</th><th>Service</th><th>Flags</th><th>Status</th><th>Order</th><th></th></tr></thead>
            <tbody>
                @forelse ($projects as $project)
                    <tr>
                        <td class="thumb-cell">@if ($project->image)<img src="{{ media_url($project->image) }}" alt="" class="thumb">@else<span class="thumb thumb-icon"><x-icon :name="$project->service?->icon" size="20" /></span>@endif</td>
                        <td><a href="{{ route('admin.projects.edit', $project) }}" class="strong">{{ $project->tr('title', 'nl') }}</a><div class="muted small">{{ $project->tr('category', 'nl') }}</div></td>
                        <td>{{ $project->service?->tr('title', 'nl') ?? '–' }}</td>
                        <td class="flags">
                            @if ($project->featured)<span class="pill pill-accent">Featured</span>@endif
                            @if ($project->highlight)<span class="pill">Highlighted</span>@endif
                            @if ($project->show_on_home)<span class="pill">Home</span>@endif
                        </td>
                        <td>@include('admin.partials.status', ['active' => $project->active])</td>
                        <td>{{ $project->sort }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-small">Edit</a>
                            @include('admin.partials.delete-button', ['action' => route('admin.projects.destroy', $project)])
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted">No projects yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
