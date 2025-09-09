@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Borrowed Item</h2>
    <form action="{{ route('borrowed_items.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Borrower Name</label>
            <input type="text" name="borrower_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Asset</label>
            <select name="asset_id" class="form-control" required>
                <option value="">Select Asset</option>
                @foreach($assets as $asset)
                    <option value="{{ $asset->id }}">{{ $asset->name }} ({{ $asset->serial_number }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Laboratory</label>
            <select name="laboratory_id" class="form-control" required>
                <option value="">Select Laboratory</option>
                @foreach($laboratories as $lab)
                    <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Date Borrowed</label>
            <input type="date" name="date_borrowed" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Date Return</label>
            <input type="date" name="return_date" class="form-control">
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="Borrowed">Borrowed</option>
                <option value="Returned">Returned</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('borrowed_items.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 