@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Borrowed Items</h2>
    <a href="{{ route('borrowed_items.create') }}" class="btn btn-primary mb-3">+ Borrow Item</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Borrower Name</th>
                <th>Asset</th>
                <th>Laboratory</th>
                <th>Date Borrowed</th>
                <th>Date Return</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowedItems as $item)
            <tr>
                <td>{{ $item->borrower_name }}</td>
                <td>{{ $item->asset->name ?? '' }}</td>
                <td>{{ $item->laboratory->name ?? '' }}</td>
                <td>{{ $item->date_borrowed }}</td>
                <td>{{ $item->return_date }}</td>
                <td>{{ $item->status }}</td>
                <td>
                    {{-- <a href="{{ route('borrowed_items.show', $item->id) }}" class="btn btn-info btn-sm">View</a> --}}
                    {{-- <a href="{{ route('borrowed_items.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a> --}}
                    <form action="{{ route('borrowed_items.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $borrowedItems->links() }}
</div>
@endsection
