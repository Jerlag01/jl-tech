@extends('layouts.admin', ['title' => 'Clients & partners'])

@section('content')
    <div class="toolbar">
        <p class="muted">Names scroll in the band under the hero; logos are shown in the "They trust us" grid on the home page.</p>
        <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">+ Add client</a>
    </div>

    <div class="panel table-wrap">
        <table class="table">
            <thead><tr><th>Logo</th><th>Name</th><th>Scrolling band</th><th>Logo grid</th><th>Status</th><th>Order</th><th></th></tr></thead>
            <tbody>
                @forelse ($clients as $client)
                    <tr>
                        <td class="thumb-cell">@if ($client->logo)<img src="{{ media_url($client->logo) }}" alt="" class="thumb thumb-contain">@else<span class="muted small">no logo</span>@endif</td>
                        <td><a href="{{ route('admin.clients.edit', $client) }}" class="strong">{{ $client->name }}</a></td>
                        <td>{{ $client->in_marquee ? 'Yes' : '–' }}</td>
                        <td>{{ $client->in_logo_grid ? ($client->logo ? 'Yes' : 'Yes (needs logo)') : '–' }}</td>
                        <td>@include('admin.partials.status', ['active' => $client->active])</td>
                        <td>{{ $client->sort }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-small">Edit</a>
                            @include('admin.partials.delete-button', ['action' => route('admin.clients.destroy', $client)])
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted">No clients yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
