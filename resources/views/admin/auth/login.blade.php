<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Log in | {{ content('company.name') }} admin</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
</head>
<body class="login-body">
    <form method="POST" action="{{ route('admin.login') }}" class="login-card">
        @csrf
        <div class="sidebar-brand login-brand">{{ content('company.name') }}<span>admin</span></div>

        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <label class="field">
            <span class="field-label">Email</span>
            <input type="email" name="email" value="{{ old('email') }}" class="input" required autofocus autocomplete="username">
        </label>
        <label class="field">
            <span class="field-label">Password</span>
            <input type="password" name="password" class="input" required autocomplete="current-password">
        </label>
        <label class="check">
            <input type="checkbox" name="remember" value="1"> Keep me logged in
        </label>
        <button type="submit" class="btn btn-primary btn-block">Log in</button>
        <a href="{{ url('/') }}" class="login-back">← Back to the website</a>
    </form>
</body>
</html>
