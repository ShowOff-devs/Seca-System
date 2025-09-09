@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Assets List</h2>
    <a href="{{ route('inventory.create') }}" class="btn btn-primary mb-3">Add New Asset</a>

    <!-- Combined Filter and Search Form -->
    <form method="GET" action="{{ route('inventory.index') }}" class="mb-3 d-flex align-items-center gap-2 asset-filter-search-bar">
        <select name="status" onchange="this.form.submit()" class="form-control w-auto me-2">
            <option value="">Filter by Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <input type="text" name="search" class="form-control w-50 me-2" placeholder="Search by Name, Serial, RFID" value="{{ request('search') }}">
        <button type="submit" class="btn btn-secondary">Search</button>
    </form>

    <!-- Asset Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>RFID No</th>
                <th>Serial Number</th>
                <th>Type</th>
                <th>Assigned Laboratory</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $asset)
            <tr>
                <td>{{ $asset->name }}</td>
                <td>{{ $asset->rfid_no }}</td>
                <td>{{ $asset->serial_number }}</td>
                <td>{{ ucfirst($asset->type) }}</td>
                <td>{{ $asset->assigned_laboratory }}</td>
                <td>{{ ucfirst($asset->status) }}</td>
                <td>
                    <a href="{{ route('inventory.show', $asset->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('inventory.edit', $asset->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('inventory.destroy', $asset->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    {{ $assets->links() }}
</div>
@endsection
