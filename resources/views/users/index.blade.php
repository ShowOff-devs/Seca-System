@extends('layouts.app')

@section('content')
<div class="container">
    <h2>User Management</h2>
    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">+ Add User</a>

    <form method="GET" action="{{ route('users.index') }}" class="mb-3 user-filter-search-bar">
        <input type="text" name="search" id="search" class="form-control" placeholder="Name or Email" value="{{ request('search') }}">
        <select name="role" id="role" class="form-control">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="sub-admin" {{ request('role') == 'sub-admin' ? 'selected' : '' }}>Sub Admin</option>
        </select>
        <button type="submit" class="btn btn-secondary">Filter</button>
        <a href="{{ route('users.index') }}" class="btn btn-light">Reset</a>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Profile Picture</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" width="40" height="40" style="object-fit:cover; border-radius:50%;">
                    @else
                        <span class="text-muted">No Image</span>
                    @endif
                </td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
</div>
@endsection 