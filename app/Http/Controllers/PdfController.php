<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\RfidLog;
use App\Models\Laboratory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    // Show the filter form and preview table
    public function index(Request $request, $report_id)
    {
        $report = Report::findOrFail($report_id);
        $laboratories = Laboratory::all();

        $query = RfidLog::whereDate('created_at', '>=', date('Y-m-d', strtotime($report->start_date)))
            ->whereDate('created_at', '<=', date('Y-m-d', strtotime($report->end_date)));

        // Laboratory filter
        $selectedLab = null;
        if ($request->filled('laboratory_id')) {
            $query->where('device_id', $request->laboratory_id);
            $selectedLab = Laboratory::find($request->laboratory_id);
        }

        $movements = $query->orderBy('created_at', 'DESC')->get();

        return view('report.index', compact('report', 'movements', 'laboratories', 'selectedLab'));
    }

    // Generate the PDF based on filters
    public function generateReport(Request $request, $report_id)
    {
        $report = Report::findOrFail($report_id);

        $query = RfidLog::whereDate('created_at', '>=', date('Y-m-d', strtotime($report->start_date)))
            ->whereDate('created_at', '<=', date('Y-m-d', strtotime($report->end_date)));

        $selectedLab = null;
        if ($request->filled('laboratory_id')) {
            $query->where('device_id', $request->laboratory_id);
            $selectedLab = \App\Models\Laboratory::find($request->laboratory_id);
        }

        $movements = $query->orderBy('created_at', 'DESC')->get();

        $pdf = Pdf::loadView('pdf.generateReport', compact('movements', 'report', 'selectedLab'))->setPaper('A4');
        return $pdf->stream();
    }
}
