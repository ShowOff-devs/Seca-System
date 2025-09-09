<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reports = Report::latest()->paginate(10);
        return view('reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_name' => 'required|string|max:255',
            'report_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'schedule_frequency' => 'nullable|string',
        ]);

        $report = Report::create($validated);
        
        if ($request->has('generate_now')) {
            return $this->generateReport($report);
        }

        return redirect()->route('reports.index')
            ->with('success', 'Report created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        return view('reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        if ($report->file_path) {
            Storage::delete($report->file_path);
        }
        
        $report->delete();
        return redirect()->route('reports.index')
            ->with('success', 'Report deleted successfully.');
    }

    public function generateReport(Report $report)
    {
        try {
            // Query assets based on report parameters
            $assets = Asset::whereBetween('created_at', [$report->start_date, $report->end_date])
                ->with(['type', 'status', 'location'])
                ->get();

            // Generate CSV content
            $csvContent = $this->generateCsvContent($assets);

            // Save to storage
            $filename = 'reports/' . $report->report_name . '_' . now()->format('Y-m-d_His') . '.csv';
            Storage::put($filename, $csvContent);

            // Update report status
            $report->update([
                'status' => Report::STATUS_COMPLETED,
                'file_path' => $filename,
                'last_generated_at' => now()
            ]);

            return response()->download(storage_path('app/' . $filename))
                ->deleteFileAfterSend();
        } catch (\Exception $e) {
            $report->update(['status' => Report::STATUS_FAILED]);
            return back()->with('error', 'Failed to generate report: ' . $e->getMessage());
        }
    }

    private function generateCsvContent($assets)
    {
        $headers = ['Asset Name', 'Serial Number', 'Type', 'Status', 'Location', 'Last Updated'];
        $rows = [];

        foreach ($assets as $asset) {
            $rows[] = [
                $asset->name,
                $asset->serial_number,
                $asset->type->name ?? 'N/A',
                $asset->status->name ?? 'N/A',
                $asset->location->name ?? 'N/A',
                $asset->updated_at->format('Y-m-d H:i:s')
            ];
        }

        $csv = implode(',', $headers) . "\n";
        foreach ($rows as $row) {
            $csv .= implode(',', $row) . "\n";
        }

        return $csv;
    }

    public function scheduleReport(Request $request, Report $report)
    {
        $validated = $request->validate([
            'schedule_frequency' => 'required|string|in:weekly,monthly,annual'
        ]);

        $report->update([
            'schedule_frequency' => $validated['schedule_frequency']
        ]);

        return back()->with('success', 'Report scheduled successfully.');
    }
}
