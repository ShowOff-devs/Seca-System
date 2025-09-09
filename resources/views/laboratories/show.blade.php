@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $laboratory->name }}</h2>

    <!-- Filters -->
    <form method="GET" action="{{ route('laboratories.show', $laboratory->id) }}">
        <select name="status" onchange="this.form.submit()">
            <option value="">Filter by Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>

        <select name="type" onchange="this.form.submit()">
            <option value="">Filter by Type</option>
            <option value="hardware">Hardware</option>
            <option value="consumables">Consumables</option>
            <option value="others">Others</option>
        </select>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>RFID</th>
                <th>Serial No</th>
                <th>Type</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($assets as $asset)
            <tr>
                <td>{{ $asset->name }}</td>
                <td>{{ $asset->rfid_no }}</td>
                <td>{{ $asset->serial_number }}</td>
                <td>{{ ucfirst($asset->type) }}</td>
                <td>{{ ucfirst($asset->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $assets->links() }}

    <a href="{{ route('laboratories.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
