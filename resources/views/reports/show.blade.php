@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Report Details</h1>
            <a href="{{ route('reports.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Reports
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Report Name</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $report->report_name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Report Type</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ ucfirst($report->report_type) }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Date Range</h3>
                        <p class="mt-1 text-lg text-gray-900">
                            {{ $report->start_date->format('M d, Y') }} - {{ $report->end_date->format('M d, Y') }}
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Status</h3>
                        <p class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $report->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($report->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    'bg-red-100 text-red-800') }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Schedule Frequency</h3>
                        <p class="mt-1 text-lg text-gray-900">
                            {{ $report->schedule_frequency ? ucfirst($report->schedule_frequency) : 'Not scheduled' }}
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Last Generated</h3>
                        <p class="mt-1 text-lg text-gray-900">
                            {{ $report->last_generated_at ? $report->last_generated_at->format('M d, Y H:i:s') : 'Never' }}
                        </p>
                    </div>
                </div>

                @if($report->status === 'completed')
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Download Options</h3>
                        <div class="flex space-x-4">
                            <a href="{{ route('reports.generate', $report) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Download CSV
                            </a>
                            @if($report->schedule_frequency)
                                <form action="{{ route('reports.schedule', $report) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Regenerate Report
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @elseif($report->status === 'failed')
                    <div class="mt-6">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">Failed to generate report. Please try again.</span>
                        </div>
                        <div class="mt-4">
                            <form action="{{ route('reports.generate', $report) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Retry Generation
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 