@extends('layouts.admin', ['title' => 'Message'])

@section('content')
    <div class="panel narrow">
        <h2 class="message-title">{{ $message->subject }}</h2>
        <dl class="details">
            <dt>From</dt><dd>{{ $message->name }}</dd>
            <dt>Email</dt><dd><a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: '.$message->subject) }}">{{ $message->email }}</a></dd>
            <dt>Phone</dt><dd>@if ($message->phone)<a href="tel:{{ $message->phone }}">{{ $message->phone }}</a>@else – @endif</dd>
            <dt>Language</dt><dd>{{ config('site.locales')[$message->locale] ?? $message->locale }}</dd>
            <dt>Received</dt><dd>{{ $message->created_at->format('d/m/Y H:i') }}</dd>
        </dl>
        <div class="message-body">{{ $message->message }}</div>

        <div class="form-actions">
            <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: '.$message->subject) }}" class="btn btn-primary">Reply by email</a>
            <form method="POST" action="{{ route('admin.messages.unread', $message) }}" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-ghost">Mark as unread</button>
            </form>
            <a href="{{ route('admin.messages.index') }}" class="btn btn-ghost">Back</a>
            @include('admin.partials.delete-button', ['action' => route('admin.messages.destroy', $message)])
        </div>
    </div>
@endsection
