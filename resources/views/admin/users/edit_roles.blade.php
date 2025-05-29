@extends('layouts.app')
@section('title', 'Edit User Roles')

@section('content')
<div class="container py-4">
    <h2 class="mb-3">Edit Roles: {{ $user->name }}</h2>

    <form method="POST" action="{{ route('admin.users.roles.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label>Assign Roles:</label><br/>
            @foreach ($roles as $role)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}"
                        {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
                    <label class="form-check-label">{{ ucfirst($role->name) }}</label>
                </div>
            @endforeach
        </div>

        <button class="btn btn-primary">Update Roles</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
