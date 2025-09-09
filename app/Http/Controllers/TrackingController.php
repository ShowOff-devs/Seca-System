<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Laboratory;
use App\Models\RfidLog;
use App\Models\TrackingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\TrackingActivityNotification;
use App\Models\User;

class TrackingController extends Controller
{

    public function search(Request $request)
{
    $search = $request->input('query');

    $laboratories = Laboratory::all();
    $moved_in = RfidLog::where('scan_type', 'IN')->whereDate('created_at', today())->count();
    $moved_out = RfidLog::where('scan_type', 'OUT')->whereDate('created_at', today())->count();

    $recentMovements = RfidLog::with(['asset', 'laboratory'])
        ->where('rfid_no', 'like', "%{$search}%")
        ->orWhereHas('asset', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        })
        ->orWhereHas('laboratory', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        })
        ->orderBy('id', 'DESC')
        ->get();

    return view('tracking.index', compact('laboratories', 'recentMovements', 'moved_in', 'moved_out', 'search'));
}



    public function index()
    {
        $laboratories = Laboratory::all();
        // $recentMovements = TrackingLog::with(['asset', 'laboratory'])
        //     ->latest()
        //     ->take(10)
        //     ->get();
        $moved_in = RfidLog::where('scan_type', 'IN')->whereDate('created_at', today())->count();
        $moved_out = RfidLog::where('scan_type', 'OUT')->whereDate('created_at', today())->count();
        $recentMovements = RfidLog::orderBy('id', 'DESC')->get();
            
        return view('tracking.index', compact('laboratories', 'recentMovements', 'moved_in', 'moved_out'));
    }

    public function handleRFIDScan(Request $request)
    {
        $request->validate([
            'rfid_no' => 'required|string',
            'laboratory_id' => 'required|exists:laboratories,id'
        ]);

        $asset = Asset::where('rfid_no', $request->rfid_no)->first();
        
        if (!$asset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asset not found'
            ], 404);
        }

        // Get the last movement for this asset
        $lastMovement = TrackingLog::where('asset_id', $asset->id)
            ->latest()
            ->first();

        // Determine movement type based on last movement
        $movementType = (!$lastMovement || $lastMovement->movement_type === 'out') ? 'in' : 'out';

        // Create new tracking log
        $trackingLog = TrackingLog::create([
            'asset_id' => $asset->id,
            'laboratory_id' => $request->laboratory_id,
            'movement_type' => $movementType,
            'timestamp' => now(),
            'rfid_no' => $request->rfid_no
        ]);

        // Notify all users about the tracking activity
        foreach (User::all() as $user) {
            $user->notify(new TrackingActivityNotification($trackingLog));
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'asset' => $asset,
                'movement' => $trackingLog,
                'laboratory' => $trackingLog->laboratory
            ]
        ]);
    }

    public function getMovementHistory(Request $request)
    {
        $query = TrackingLog::with(['asset', 'laboratory']);

        if ($request->has('laboratory_id')) {
            $query->where('laboratory_id', $request->laboratory_id);
        }

        if ($request->has('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }

        if ($request->has('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        if ($request->has('date_from')) {
            $query->whereDate('timestamp', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('timestamp', '<=', $request->date_to);
        }

        $movements = $query->latest()->paginate(15);

        return response()->json($movements);
    }

    public function getCurrentStatus()
    {
        $currentStatus = DB::table('tracking_logs as t1')
            ->select('assets.id', 'assets.name', 'assets.rfid_no', 'laboratories.name as laboratory_name', 't1.movement_type', 't1.timestamp')
            ->join('assets', 'assets.id', '=', 't1.asset_id')
            ->join('laboratories', 'laboratories.id', '=', 't1.laboratory_id')
            ->whereIn('t1.id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('tracking_logs')
                    ->groupBy('asset_id');
            })
            ->get();

        return response()->json($currentStatus);
    }
}
