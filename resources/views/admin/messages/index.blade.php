@extends('layouts.admin', ['title' => 'Messages'])

@section('content')
    <div class="panel table-wrap">
        <table class="table">
            <thead><tr><th>From</th><th>Subject</th><th>Language</th><th>Received</th><th></th></tr></thead>
            <tbody>
                @forelse ($messages as $message)
                    <tr @class(['unread' => ! $message->isRead()])>
                        <td><a href="{{ route('admin.messages.show', $message) }}" class="strong">{{ $message->name }}</a><div class="muted small">{{ $message->email }}</div></td>
                        <td><a href="{{ route('admin.messages.show', $message) }}">{{ $message->subject }}</a></td>
                        <td>{{ strtoupper($message->locale ?? '') }}</td>
                        <td title="{{ $message->created_at }}">{{ $message->created_at->format('d/m/Y H:i') }}</td>
                        <td class="actions">@include('admin.partials.delete-button', ['action' => route('admin.messages.destroy', $message)])</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">No messages yet. Messages sent through the contact form appear here.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $messages->links('admin.partials.pagination') }}
@endsection
