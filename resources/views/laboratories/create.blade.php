@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Laboratory</h2>
    <form action="{{ route('laboratories.store') }}" method="POST">
        @csrf

        <!-- Laboratory Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Laboratory Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <!-- Laboratory Location -->
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="{{ old('location') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('laboratories.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
