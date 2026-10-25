@extends('layouts.admin', ['title' => 'Admin users'])

@section('content')
    <div class="toolbar">
        <p class="muted">Everyone listed here can log in to this admin.</p>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Add user</a>
    </div>

    <div class="panel table-wrap">
        <table class="table">
            <thead><tr><th>Name</th><th>Email</th><th>Created</th><th></th></tr></thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="strong">{{ $user->name }} @if ($user->is(auth()->user()))<span class="pill">you</span>@endif</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at?->format('d/m/Y') }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-small">Edit</a>
                            @unless ($user->is(auth()->user()))
                                @include('admin.partials.delete-button', ['action' => route('admin.users.destroy', $user)])
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
