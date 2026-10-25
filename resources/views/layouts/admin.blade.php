@php
    $contentGroups = collect(config('content'))->reject(fn ($g) => $g['settings'] ?? false);
    $settingsGroups = collect(config('content'))->filter(fn ($g) => $g['settings'] ?? false);
    $unread = \App\Models\ContactMessage::query()->whereNull('read_at')->count();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ?? 'Admin' }} | {{ content('company.name') }} admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
</head>
<body>
<div class="admin">
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">{{ content('company.name') }}<span>admin</span></a>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>Dashboard</a>
            <a href="{{ route('admin.messages.index') }}" @class(['active' => request()->routeIs('admin.messages.*')])>
                Messages @if ($unread)<span class="badge">{{ $unread }}</span>@endif
            </a>

            <div class="sidebar-heading">Page content</div>
            @foreach ($contentGroups as $key => $group)
                <a href="{{ route('admin.content.edit', $key) }}" @class(['active' => request()->routeIs('admin.content.*') && request()->route('group') === $key])>{{ $group['label'] }}</a>
            @endforeach

            <div class="sidebar-heading">Collections</div>
            <a href="{{ route('admin.services.index') }}" @class(['active' => request()->routeIs('admin.services.*')])>Services</a>
            <a href="{{ route('admin.projects.index') }}" @class(['active' => request()->routeIs('admin.projects.*')])>Projects</a>
            <a href="{{ route('admin.clients.index') }}" @class(['active' => request()->routeIs('admin.clients.*')])>Clients & partners</a>
            <a href="{{ route('admin.jobs.index') }}" @class(['active' => request()->routeIs('admin.jobs.*')])>Job offers</a>

            <div class="sidebar-heading">Page blocks</div>
            @foreach (config('site.item_groups') as $key => $group)
                <a href="{{ route('admin.items.index', $key) }}" @class(['active' => (request()->route('group') === $key) || (request()->route('item')?->group === $key)])>{{ $group['label'] }}</a>
            @endforeach

            <div class="sidebar-heading">Settings</div>
            @foreach ($settingsGroups as $key => $group)
                <a href="{{ route('admin.content.edit', $key) }}" @class(['active' => request()->routeIs('admin.content.*') && request()->route('group') === $key])>{{ $group['label'] }}</a>
            @endforeach
            <a href="{{ route('admin.users.index') }}" @class(['active' => request()->routeIs('admin.users.*')])>Admin users</a>
        </nav>
    </aside>

    <div class="main">
        <header class="topbar">
            <button type="button" class="menu-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')" aria-label="Menu">☰</button>
            <div class="topbar-title">{{ $title ?? 'Admin' }}</div>
            <div class="topbar-actions">
                <a href="{{ url('/') }}" target="_blank" rel="noopener" class="btn btn-ghost">View website ↗</a>
                <span class="topbar-user">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost">Log out</button>
                </form>
            </div>
        </header>

        <main class="content">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>Please check the form:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
<script>
    // Ask before deleting
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!confirm(form.getAttribute('data-confirm'))) {
                event.preventDefault();
            }
        });
    });
    // Preview a newly picked image
    document.querySelectorAll('input[type=file][data-preview]').forEach(function (input) {
        input.addEventListener('change', function () {
            var preview = document.getElementById(input.getAttribute('data-preview'));
            if (preview && input.files[0]) {
                preview.src = URL.createObjectURL(input.files[0]);
                preview.hidden = false;
            }
        });
    });
</script>
</body>
</html>
