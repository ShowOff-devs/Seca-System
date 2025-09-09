<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RfidLog;
use App\Events\RfidScanned;

class RfidLogController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'rfid_no'    => 'required|string',
            'device_id'  => 'required|string',
            'scan_type'  => 'required|in:IN,OUT',
            'scanned_at' => 'required|date',
        ]);

        $log = RfidLog::create($data);

        // Broadcast event
        broadcast(new RfidScanned($log))->toOthers();

        return response()->json(['status' => 'ok'], 201);
    }
}
