@extends('layouts.admin', ['title' => $user->exists ? 'Edit user' : 'New user'])

@section('content')
    <form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" class="form">
        @csrf
        @if ($user->exists) @method('PUT') @endif

        <div class="panel narrow">
            @include('admin.partials.field', ['name' => 'name', 'label' => 'Name', 'value' => $user->name, 'required' => true])
            @include('admin.partials.field', ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'value' => $user->email, 'required' => true])
            <label class="field">
                <span class="field-label">Password @unless ($user->exists)<span class="req">*</span>@endunless</span>
                <input type="password" name="password" class="input" autocomplete="new-password">
                <span class="field-help">{{ $user->exists ? 'Leave empty to keep the current password.' : 'At least 8 characters.' }}</span>
            </label>
            <label class="field">
                <span class="field-label">Repeat password</span>
                <input type="password" name="password_confirmation" class="input" autocomplete="new-password">
            </label>
        </div>

        @include('admin.partials.form-actions', ['back' => route('admin.users.index')])
    </form>
@endsection
