@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Create New Report</h1>

        <form action="{{ route('reports.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="report_name">
                    Report Name
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('report_name') border-red-500 @enderror"
                    id="report_name" type="text" name="report_name" value="{{ old('report_name') }}" required>
                @error('report_name')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="report_type">
                    Report Type
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('report_type') border-red-500 @enderror"
                    id="report_type" name="report_type" required>
                    <option value="">Select Report Type</option>
                    <option value="monthly" {{ old('report_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="weekly" {{ old('report_type') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="annual" {{ old('report_type') == 'annual' ? 'selected' : '' }}>Annual</option>
                    <option value="custom" {{ old('report_type') == 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
                @error('report_type')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">
                    Start Date
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('start_date') border-red-500 @enderror"
                    id="start_date" type="date" name="start_date" value="{{ old('start_date') }}" required>
                @error('start_date')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">
                    End Date
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('end_date') border-red-500 @enderror"
                    id="end_date" type="date" name="end_date" value="{{ old('end_date') }}" required>
                @error('end_date')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            {{-- <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="schedule_frequency">
                    Schedule Frequency (Optional)
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="schedule_frequency" name="schedule_frequency">
                    <option value="">No Schedule</option>
                    <option value="weekly" {{ old('schedule_frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ old('schedule_frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="annual" {{ old('schedule_frequency') == 'annual' ? 'selected' : '' }}>Annual</option>
                </select>
            </div> --}}

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="generate_now" value="1">
                    Generate Report
                </button>
                <a href="{{ route('reports.index') }}" class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection 