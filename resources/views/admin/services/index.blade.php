@extends('layouts.admin', ['title' => 'Services'])

@section('content')
    <div class="toolbar">
        <p class="muted">Shown on the Services page, in the navigation menu and (when enabled) on the home page.</p>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">+ Add service</a>
    </div>

    <div class="panel table-wrap">
        <table class="table">
            <thead><tr><th></th><th>Title</th><th>Projects</th><th>Home</th><th>Status</th><th>Order</th><th></th></tr></thead>
            <tbody>
                @forelse ($services as $service)
                    <tr>
                        <td class="thumb-cell">@if ($service->image)<img src="{{ media_url($service->image) }}" alt="" class="thumb">@else<span class="thumb thumb-icon"><x-icon :name="$service->icon" size="20" /></span>@endif</td>
                        <td><a href="{{ route('admin.services.edit', $service) }}" class="strong">{{ $service->tr('title', 'nl') }}</a><div class="muted small">{{ $service->tr('title', 'en') }}</div></td>
                        <td>{{ $service->projects_count }}</td>
                        <td>{{ $service->show_on_home ? 'Yes' : '–' }}</td>
                        <td>@include('admin.partials.status', ['active' => $service->active])</td>
                        <td>{{ $service->sort }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-small">Edit</a>
                            @include('admin.partials.delete-button', ['action' => route('admin.services.destroy', $service)])
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted">No services yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
