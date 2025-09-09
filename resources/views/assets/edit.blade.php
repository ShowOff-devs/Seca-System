@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Asset</h2>
    <form action="{{ route('inventory.update', $asset->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $asset->name }}" required>
        </div>
        <div class="mb-3">
            <label>RFID No</label>
            <input type="text" name="rfid_no" class="form-control" value="{{ $asset->rfid_no }}">
        </div>
        <div class="mb-3">
            <label>Serial Number</label>
            <input type="text" name="serial_number" class="form-control" value="{{ $asset->serial_number }}">
        </div>
        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-control">
                <option value="hardware" {{ $asset->type == 'hardware' ? 'selected' : '' }}>Hardware</option>
                <option value="consumables" {{ $asset->type == 'consumables' ? 'selected' : '' }}>Consumables</option>
                <option value="others" {{ $asset->type == 'others' ? 'selected' : '' }}>Others</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Assigned Laboratory</label>
            <input type="text" name="assigned_laboratory" class="form-control" value="{{ $asset->laboratory->name }}">
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="active" {{ $asset->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $asset->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update Asset</button>
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
