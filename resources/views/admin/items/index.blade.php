@extends('layouts.admin', ['title' => $config['label']])

@section('content')
    <div class="toolbar">
        <p class="muted">
            Blocks on the <a href="{{ page_url($config['page'], 'nl') }}" target="_blank" rel="noopener">{{ __('page.'.$config['page'], [], 'en') }}</a> page.
            Section titles are edited under Page content.
        </p>
        <a href="{{ route('admin.items.create', $group) }}" class="btn btn-primary">+ Add block</a>
    </div>

    <div class="panel table-wrap">
        <table class="table">
            <thead>
                <tr>
                    @isset($config['fields']['value'])<th>{{ $config['fields']['value'] }}</th>@endisset
                    <th>{{ $config['fields']['title'] }}</th>
                    <th>Status</th><th>Order</th><th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        @isset($config['fields']['value'])<td class="strong">{{ $item->value }}</td>@endisset
                        <td>
                            <a href="{{ route('admin.items.edit', $item) }}" class="strong">{{ $item->tr('title', 'nl') }}</a>
                            @isset($config['fields']['subtitle'])<div class="muted small">{{ $item->tr('subtitle', 'nl') }}</div>@endisset
                        </td>
                        <td>@include('admin.partials.status', ['active' => $item->active])</td>
                        <td>{{ $item->sort }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-small">Edit</a>
                            @include('admin.partials.delete-button', ['action' => route('admin.items.destroy', $item)])
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">No blocks yet. This section is hidden on the website until you add one.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
