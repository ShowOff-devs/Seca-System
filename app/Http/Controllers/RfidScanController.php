<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RfidScan;
use App\Models\Asset;
use App\Models\Log;
use App\Models\RfidLog;
use Illuminate\Support\Facades\Log as LaravelLog;

class RfidScanController extends Controller
{
    public function store(Request $request)
    {
        // 🔧 Log incoming request for debugging
        Log::info('Incoming RFID Scan:', $request->all());

        // ✅ Updated validation to accept IN and OUT
        $request->validate([
            'rfid_no' => 'required|string',
            'device_id' => 'required|string',
            'scan_type' => 'required|in:IN,OUT',
            'scanned_at' => 'required|date'
        ]);

        // 🔍 Check if asset exists for this RFID number
        $asset = Asset::where('rfid_no', $request->rfid_no)->first();

        if (!$asset) {
            Log::warning("Asset not found for RFID: " . $request->rfid_no);
            return response()->json([
                'error' => 'Asset not found with this RFID number'
            ], 404);
        }

        // 📝 Create RFID scan record
        $scan = RfidScan::create($request->all());

        // 📌 Log the event
        Log::info("Asset '{$asset->name}' scanned as {$request->scan_type}");

        Log::create([
            'description' => "Asset '{$asset->name}' was scanned ({$request->scan_type}) at device {$request->device_id}"
        ]);

        return response()->json([
            'message' => 'Scan recorded successfully',
            'scan' => $scan,
            'asset' => $asset
        ]);
    }

    public function index(Request $request)
    {
        
        // Validate incoming JSON
        $validated = $request->validate([
            'rfid_no' => 'required|string',
            'laboratory' => 'required',
        ]);
        $rfid = RfidLog::where('rfid_no', $request->rfid_no)->where('device_id', $request->laboratory)->orderBy('id', 'DESC')->first();
        if($rfid && $rfid->scan_type == "IN") {
            RfidLog::create([
                'rfid_no' => $request->rfid_no,
                'device_id' => $request->laboratory,
                'scan_type' => 'OUT',
            ]);
        } else {
            RfidLog::create([
                'rfid_no' => $request->rfid_no,
                'device_id' => $request->laboratory,
                'scan_type' => 'IN',
            ]);
        }
        
        return response()->json([
            'message' => 'RFID scan received',
            'data' => $validated,
        ]);

    }
}
