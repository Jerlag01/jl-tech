@extends('layouts.admin', ['title' => 'Job offers'])

@section('content')
    <div class="toolbar">
        <p class="muted">Visible job offers are listed on the Careers page. Without any, the page invites open applications.</p>
        <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary">+ Add job offer</a>
    </div>

    <div class="panel table-wrap">
        <table class="table">
            <thead><tr><th>Title</th><th>Badge</th><th>Status</th><th>Order</th><th></th></tr></thead>
            <tbody>
                @forelse ($jobs as $job)
                    <tr>
                        <td><a href="{{ route('admin.jobs.edit', $job) }}" class="strong">{{ $job->tr('title', 'nl') }}</a><div class="muted small">{{ $job->tr('title', 'en') }}</div></td>
                        <td>{{ $job->tr('badge', 'nl') ?: '–' }}</td>
                        <td>@include('admin.partials.status', ['active' => $job->active])</td>
                        <td>{{ $job->sort }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-small">Edit</a>
                            @include('admin.partials.delete-button', ['action' => route('admin.jobs.destroy', $job)])
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">No job offers.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
