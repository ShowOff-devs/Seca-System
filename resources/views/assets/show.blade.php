@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Asset Details</h2>
    <p><strong>Name:</strong> {{ $asset->name }}</p>
    <p><strong>RFID No:</strong> {{ $asset->rfid_no }}</p>
    <p><strong>Serial Number:</strong> {{ $asset->serial_number }}</p>
    <p><strong>Type:</strong> {{ ucfirst($asset->type) }}</p>
    <p><strong>Assigned Laboratory:</strong> {{ $asset->assigned_laboratory }}</p>
    <p><strong>Status:</strong> {{ ucfirst($asset->status) }}</p>
    <a href="{{ route('inventory.index') }}" class="btn btn-primary">Back to List</a>
</div>
@endsection
