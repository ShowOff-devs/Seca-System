@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Laboratories</h2>
    <a href="{{ route('laboratories.create') }}" class="btn btn-primary mb-3">+ Add Laboratory</a>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Assets</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laboratories as $lab)
            <tr>
                <td>{{ $lab->name }}</td>
                <td>{{ $lab->assets_count }}</td>
                <td>
                    <a href="{{ route('laboratories.show', $lab->id) }}" class="btn btn-info">View</a>
                    <a href="{{ route('laboratories.edit', $lab->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('laboratories.destroy', $lab->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this lab?');">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $laboratories->links() }}
</div>
@endsection
