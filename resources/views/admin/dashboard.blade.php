@extends('layouts.admin', ['title' => 'Dashboard'])

@section('content')
    <div class="stat-cards">
        <a href="{{ route('admin.messages.index') }}" class="stat-card stat-card-accent">
            <span class="stat-card-value">{{ $unreadCount }}</span>
            <span class="stat-card-label">Unread messages</span>
        </a>
        @foreach ($counts as $label => [$count, $url])
            <a href="{{ $url }}" class="stat-card">
                <span class="stat-card-value">{{ $count }}</span>
                <span class="stat-card-label">{{ $label }}</span>
            </a>
        @endforeach
    </div>

    <div class="grid-2">
        <section class="panel">
            <div class="panel-header">
                <h2>Latest messages</h2>
                <a href="{{ route('admin.messages.index') }}">All messages →</a>
            </div>
            @forelse ($latestMessages as $message)
                <a href="{{ route('admin.messages.show', $message) }}" @class(['message-row', 'unread' => ! $message->isRead()])>
                    <span class="message-from">{{ $message->name }}</span>
                    <span class="message-subject">{{ $message->subject }}</span>
                    <span class="muted">{{ $message->created_at->diffForHumans() }}</span>
                </a>
            @empty
                <p class="muted">No messages yet.</p>
            @endforelse
        </section>

        <section class="panel">
            <div class="panel-header"><h2>Quick links</h2></div>
            <ul class="quick-links">
                <li><a href="{{ route('admin.content.edit', 'home') }}">Edit the home page texts</a></li>
                <li><a href="{{ route('admin.services.create') }}">Add a service</a></li>
                <li><a href="{{ route('admin.projects.create') }}">Add a project</a></li>
                <li><a href="{{ route('admin.jobs.create') }}">Publish a job offer</a></li>
                <li><a href="{{ route('admin.content.edit', 'contact') }}">Update contact details</a></li>
                <li><a href="{{ route('admin.content.edit', 'branding') }}">Upload the logo</a></li>
            </ul>
            <p class="muted small">Every text is available in Dutch and English. When one language is left empty, the website shows the other one.</p>
        </section>
    </div>
@endsection
