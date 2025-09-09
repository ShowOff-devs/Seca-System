@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Asset Reports</h1>
        <a href="{{ route('reports.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Create New Report
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Report Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Date Range</th>
                    {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Schedule</th> --}}
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($reports as $report)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $report->report_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ ucfirst($report->report_type) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                            {{ $report->start_date->format('M d, Y') }} - {{ $report->end_date->format('M d, Y') }}
                        </td>
                        {{-- <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $report->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($report->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    'bg-red-100 text-red-800') }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                            {{ $report->schedule_frequency ? ucfirst($report->schedule_frequency) : 'Not scheduled' }}
                        </td> --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <div class="flex space-x-2">
                                <a href="{{ route('generate-report', $report->id) }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-1.5 px-4 rounded-full text-sm">View</a>
                                <a href="{{ route('reports.edit', $report) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1.5 px-4 rounded-full text-sm">Edit</a>
                                <form action="{{ route('reports.destroy', $report) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1.5 px-4 rounded-full text-sm" onclick="return confirm('Are you sure you want to delete this report?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $reports->links() }}
    </div>
</div>
@endsection 